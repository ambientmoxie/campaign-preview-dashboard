// A simple class that adds a password visibility toggle to a form.
// Takes one form element and an optional config object with one key:
// isVisible (boolean) – sets whether the password is visible by default.

class PasswordToggler {
  constructor(form, config = {}) {
    this.form = form;
    if (!this.form) return;

    this.input = this.form.querySelector(".form__input");
    this.btn = this.form.querySelector(".form__button--toggler");
    this.showIcon = this.btn.querySelector(".icon--show") || null;
    this.hideIcon = this.btn.querySelector(".icon--hide") || null;

    this.isVisible = config.isVisible ?? false;

    this.init();
  }

  init = () => {
    this.setDefaultState();
    this.btn.addEventListener("click", () => {
      this.togglePassword();
    });
  };

  setDefaultState = () => {
    if (this.showIcon && this.hideIcon) {
      this.hideIcon.style.display = this.isVisible ? "block" : "none";
      this.showIcon.style.display = this.isVisible ? "none" : "block";
    }

    this.input.type = this.isVisible ? "text" : "password";
    this.btn.textContent = this.isVisible ? "Hide password" : "Show Password";
  };

  togglePassword = () => {
    const isPassword = this.input.type === "password";
    this.input.type = isPassword ? "text" : "password";

    if (this.showIcon && this.hideIcon) {
      this.showIcon.style.display = isPassword ? "none" : "inline";
      this.hideIcon.style.display = isPassword ? "inline" : "none";
    }

    this.btn.textContent = isPassword ? "Hide password" : "Show password";
  };
}

export default function initPasswordToggler() {
  const form = document.querySelector("form");
  new PasswordToggler(form, { isVisible: false });
}
