/**
 * A small utility class to toggle the dashboard panel on and off, 
 * adjusting panel width and padding while keeping the board’s spacing even.
 */

class PanelToggler {
  constructor(buttonElement, config = {}) {
    this.button = buttonElement;
    this.isOpen = config.initiallyOpen ?? false;

    this.panel = document.querySelector("#panel");
    this.board = document.querySelector("#board");
    this.projectSection = document.querySelector("#dashboard");

    if (!this.projectSection || !this.panel || !this.board || !this.button) return;

    const projectStyles = window.getComputedStyle(this.projectSection);
    const panelStyles   = window.getComputedStyle(this.panel);
    const boardStyles   = window.getComputedStyle(this.board);

    this.initialPanelWidth   = projectStyles.getPropertyValue("--panel-width") || "30rem";
    this.initialPanelPadding = panelStyles.getPropertyValue("padding").trim();

    this.initialBoardPadding     = boardStyles.getPropertyValue("padding").trim();
    this.initialBoardPaddingLeft = boardStyles.getPropertyValue("padding-left").trim();

    this.init();
  }

  init = () => {
    this.togglePanel(this.isOpen);
    this.button.addEventListener("click", this.handleClick);
  };

  handleClick = () => {
    this.isOpen = !this.isOpen;
    this.togglePanel(this.isOpen);
  };

  hidePanel = () => {
    this.projectSection.style.setProperty("--panel-width", "0rem");
    this.panel.style.padding = "0";

    this.board.style.padding     = this.initialBoardPadding;
    this.board.style.paddingLeft = this.initialPanelPadding;
  };

  displayPanel = () => {
    this.projectSection.style.setProperty("--panel-width", this.initialPanelWidth);
    this.panel.style.padding = this.initialPanelPadding;

    this.board.style.padding     = this.initialBoardPadding;
    this.board.style.paddingLeft = "0";
  };

  togglePanel(isOpen) {
    if (isOpen) {
      this.displayPanel();
    } else {
      this.hidePanel();
    }
  }
}

export default function initTogglePanel() {
  const toggler = document.querySelector(".btn--settings");
  if (!toggler) return;
  new PanelToggler(toggler, { initiallyOpen: true });
}
