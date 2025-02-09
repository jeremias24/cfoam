import { Modal } from "bootstrap";

const showModal = (modalEl: HTMLElement | null): void => {

  if (!modalEl) {
    return;
  }

  const myModal = new Modal(modalEl);
  myModal?.show();
};

const hideModal = (modalEl: HTMLElement | null): void => {
  if (!modalEl) {
    return;
  }

  const myModal = Modal.getInstance(modalEl);
  myModal?.hide();
};

const removeModalBackdrop = (): void => {
  if (document.querySelectorAll(".modal-backdrop.fade.show").length) {
    document.querySelectorAll(".modal-backdrop.fade.show").forEach((item) => {
      item.remove();
    });
  }
};

export { removeModalBackdrop, showModal, hideModal };
