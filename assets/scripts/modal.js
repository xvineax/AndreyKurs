document.addEventListener("DOMContentLoaded", () => {
  const authDialog = document.getElementById("auth-modal");
  const contactsDialog = document.getElementById("contacts-modal");
  const profileEditDialog = document.getElementById("profile-edit-modal");
  const projectDialog = document.getElementById("project-modal");

  function openDialog(dialog) {
    if (!dialog) return;

    if (dialog.showModal) {
      if (!dialog.open) dialog.showModal();
    } else {
      dialog.setAttribute("open", "open");
    }

    document.body.style.overflow = "hidden";
  }

  function closeDialog(dialog) {
    if (!dialog) return;

    if (dialog.close) {
      if (dialog.open) dialog.close();
    } else {
      dialog.removeAttribute("open");
    }

    document.body.style.overflow = "";
  }

  function closeByBackground(dialog, modalBox) {
    if (!dialog || !modalBox) return;

    dialog.addEventListener("click", (event) => {
      if (!modalBox.contains(event.target)) {
        closeDialog(dialog);
      }
    });

    dialog.addEventListener("close", () => {
      document.body.style.overflow = "";
    });
  }

  if (authDialog) {
    const loginTab = authDialog.querySelector(".auth-tab-login");
    const registerTab = authDialog.querySelector(".auth-tab-register");
    const loginForm = authDialog.querySelector(".auth-form-login");
    const registerForm = authDialog.querySelector(".auth-form-register");

    function showLoginForm() {
      if (!loginTab || !registerTab || !loginForm || !registerForm) return;

      loginTab.classList.add("is-active");
      registerTab.classList.remove("is-active");
      loginForm.hidden = false;
      registerForm.hidden = true;
    }

    function showRegisterForm() {
      if (!loginTab || !registerTab || !loginForm || !registerForm) return;

      registerTab.classList.add("is-active");
      loginTab.classList.remove("is-active");
      registerForm.hidden = false;
      loginForm.hidden = true;
    }

    document.querySelectorAll(".open-auth").forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        showLoginForm();
        openDialog(authDialog);
      });
    });

    authDialog.querySelectorAll(".close-auth").forEach((button) => {
      button.addEventListener("click", () => closeDialog(authDialog));
    });

    if (loginTab) loginTab.addEventListener("click", showLoginForm);
    if (registerTab) registerTab.addEventListener("click", showRegisterForm);

    authDialog.querySelectorAll(".switch-login").forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        showLoginForm();
      });
    });

    authDialog.querySelectorAll(".switch-register").forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        showRegisterForm();
      });
    });

    closeByBackground(authDialog, authDialog.querySelector(".auth-modal"));
    showLoginForm();
  }

  if (contactsDialog) {
    document.querySelectorAll(".open-contacts").forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        openDialog(contactsDialog);
      });
    });

    contactsDialog.querySelectorAll(".close-contacts").forEach((button) => {
      button.addEventListener("click", () => closeDialog(contactsDialog));
    });

    closeByBackground(contactsDialog, contactsDialog.querySelector(".contacts-modal"));
  }

  if (profileEditDialog) {
    document.querySelectorAll(".open-profile-edit").forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        openDialog(profileEditDialog);
      });
    });

    profileEditDialog.querySelectorAll(".close-profile-edit").forEach((button) => {
      button.addEventListener("click", () => closeDialog(profileEditDialog));
    });

    closeByBackground(profileEditDialog, profileEditDialog.querySelector(".profile-edit-modal"));
  }

  if (projectDialog) {
    document.querySelectorAll(".open-project").forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        openDialog(projectDialog);
      });
    });

    projectDialog.querySelectorAll(".close-project").forEach((button) => {
      button.addEventListener("click", () => closeDialog(projectDialog));
    });

    closeByBackground(projectDialog, projectDialog.querySelector(".project-modal"));
  }
});
