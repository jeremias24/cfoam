<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Services\MailService;

class Service
{

    protected $controller;
    protected $model;
    protected $data;

    public function controller($controller)
    {
       
        $this->controller = App::make($controller);

        return $this;
    }

    public function model($model)
    {

        $this->model = $model;

        return $this;

    }

    public function query($option)
    {

        switch ($option->show) {
            case 'filter':
                $this->data = $this->model
                    ->getWhere($option->filter)
                    ->result();
                break;
            default:
                $this->data = $this->model
                    ->getAll()
                    ->result();
        }

        return $this;

    }

    public function relation($model, $table, $foreignKey, $primaryKey)
    {

        $this->model = new $model;

        $groupedKeys = $this->data->mapToGroups(function ($value, $key) use ($primaryKey) {
            return [$primaryKey => $value[$primaryKey]];
        });

        // if (!is_null($groupedKeys->get($primaryKey))) {
            $relationsData = $this->model->getWhereIn((object) [
                'key' => $foreignKey,
                'value' => $groupedKeys->get($primaryKey)->toArray(),
            ])
            ->result();
        
            $this->data->each(function ($item) use ($relationsData, $table, $foreignKey, $primaryKey) {
                $data = collect([]);

                if (!is_null($relationsData)) {
                    $relationsData->each(function ($value) use ($item, $table, $foreignKey, $primaryKey, $data) {
                        if ($item->{$primaryKey} == $value->{$foreignKey}) {
                            $data->push((object) $value->toArray());
                        }
                    });
                }
                
                $item->setAttribute($table, $data);
            });
        // }
        
        return $this;

    }

    public function insert($request)
    {
        try {
            $response = (object) $this->controller
                ->store(new Request($request));

            if ($response->status == 'Failed') {
                throw new \Exception('Inserting has failed');
            }

            $this->data = $response;
        } catch (\Exception $error) {
            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;

    }

    public function update($request, $option = null)
    {
        try {
            $response = (object) $this->controller
                ->update(new Request($request), $option);

            if ($response->status == 'Failed') {
                throw new \Exception('Updating has failed');
            }

            $this->data = $response;
        } catch (\Exception $error) {
            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

       return $this;
    }

    public function updateOrCreate($request, $option = null) {
    
        try {
            $response = (object) $this->controller
                ->updateOrCreate(new Request($request));

            if ($response->status == 'Failed') {
                throw new \Exception('Inserting has failed');
            }

            $this->data = $response;
        } catch (\Exception $error) {
            $this->data = (object) [
                'status' => 'Failed',
                'error' => $error->getMessage(),
            ];
        }

        return $this;
    }

    public function generateId($prefix = null, $filter = null)
    {
        $data = $this->model->getWhere($filter)->result();

        $results = $data->map(function ($item) {
            return substr(str_pad($item['series_date'], 4, '0', STR_PAD_LEFT), 2, 2) == date('y') ? $item['series_no'] : null;
        });
        
        $seriesDate = date('my');
        $seriesNo = str_pad($results->max() + 1, 3, '0', STR_PAD_LEFT);

        if (Auth::user()->user_class_id == 1) {
            $code = Auth::user()->section_id; 
            $abv = 'I';
        } else if(Auth::user()->user_class_id == 2) {
            $code = Auth::user()->dealer_id; 
            $abv = 'D';
        } else {
            $code = Auth::user()->organization_id; 
            $abv = 'O';
        }

        $generatedId = (!is_null($prefix) ? strtoupper($prefix) . '-' : '') . $abv . str_pad($code, 3, '0', STR_PAD_LEFT) . '-' . $seriesDate . '-' .  $seriesNo;

        $this->data = (object) [
            'series_date' => $seriesDate,
            'series_no' =>  $seriesNo,
            'generated_id' => $generatedId,
        ];

        return $this;

    }

    public function caseConversion($data, $case)
    {
        $this->data = [];
        if ($case == 'camel') {
            foreach ($data->toArray() as $key => $value) {
                $this->data[Str::camel($key)] = $value;
            } 
        }

        return $this;
    }
    
    public function result()
    {
        
        return $this->data;

    }

    public function uploadFile($files)
    {
        
            $uploadedFiles = [];

            foreach ($files as $item) {

                
                // Convert $item to an object for easier property access
                $data = (object) $item;

                // return public_path($data->filepath);

                // Prepare the file path (here, we don't append the referenceId,
                // since you'll add it later in the controller or service layer)
                $filepath = Str::replaceFirst('storage/', '', $data->filepath);

                // Check if the directory exists, and if not, create it
                if (!Storage::exists($filepath)) {
                    Storage::makeDirectory($filepath, 0777, true, true);
                    // Set permission for the directory using public_path
                    chmod(public_path($data->filepath), 0777);
                }

                // Move the uploaded file to the target directory
                $data->file->move(public_path($data->filepath), $data->filename);

                // Update permissions for the folder and the file
                // chmod(public_path($data->filepath), 0777);
                // chmod(public_path($data->filepath) . '/' . $data->filename, 0777);

                // If the source type indicates an upload, add the file details to the array
                if ($data->{Str::camel('source_type')} === 'upload') {
                    $uploadedFiles[] = [
                        'filename'                 => $data->filename,
                        'filetype'                 => $data->filetype,
                        'filesize'                 => $data->filesize,
                        'filepath'                 => $data->filepath . $data->filename, // Directory path where the file is stored
                        Str::camel('mime_type')    => $data->file->getClientMimeType(),
                        Str::camel('last_modified')=> $data->{Str::camel('last_Modified')}
                    ];
                }
            }

            // Return the array of processed attachment details
            return $uploadedFiles;
            
        

          
    }

    public function mail($option)
    {

        Mail::send(new MailService($option));

    } 
}