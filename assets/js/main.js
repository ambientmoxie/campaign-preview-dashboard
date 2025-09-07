import "../scss/main.scss";
import logout from "./modules/logout";
import initSandbox from "./modules/sandbox";
import initModeManager from "./modules/mode-manager";
import initCustomSelect from "./modules/custom-select";
import initTogglePanel from "./modules/toggle-dashboard";
import initPasswordToggler from "./modules/toggle-password";

document.addEventListener("DOMContentLoaded", () => {
  const pageId = document.querySelector(".page")?.id;

  if (!pageId) return;

  if (pageId === "login") {
    initPasswordToggler();
  }

  if (pageId === "sandbox") {
    initSandbox();
  }

  if (pageId === "project") {
    initCustomSelect();
    initTogglePanel();
    initModeManager();
    logout();
  }

  if (pageId === "admin") {
    logout();
  }
});
