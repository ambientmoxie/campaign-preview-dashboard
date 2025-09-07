class ModeManager {
  constructor(
    viewModeSwitch,
    bannerSizeList,
    bannerPreviewBoard,
    clearButton,
    config = {}
  ) {
    this.viewModeSwitch = viewModeSwitch;
    this.modeOptions = viewModeSwitch.querySelectorAll("button[data-mode]");
    this.buttons = bannerSizeList.querySelectorAll(".ratio-list__button");
    this.banners = bannerPreviewBoard.querySelectorAll(".banner");

    this.clearButton = clearButton;
    this.isCleared = false;

    this.isMultiMode =
      config.isMultiMode === undefined ? true : config.isMultiMode;

    this.init();
  }

  // Initializes the manager by setting the initial UI state,
  // wiring up mode, ratio, and clear-all button event listeners.
  init = () => {
    this.updateUI();
    this.clearButton.textContent = "clear all";

    this.modeOptions.forEach((modeButton) => {
      modeButton.addEventListener("click", this.selectMode);
    });

    this.buttons.forEach((button) => {
      button.addEventListener("click", this.toggleButtonSelection);
    });

    this.clearButton.addEventListener("click", (e) => {
      e.stopPropagation();
      this.handleClearSelection();
    });
  };

  // Refreshes the UI by syncing button states and updating the preview board.
  updateUI = () => {
    this.syncButtonStates();
    this.updateBoard();
  };

  // Handles switching between single and multi mode. Updates flags,
  // resets the clear-all button, and refreshes the UI.
  selectMode = (e) => {
    const mode = e.currentTarget.dataset.mode;
    if (!mode) return; // ignore clicks from non-mode buttons

    this.modeOptions.forEach((modeButton) =>
      modeButton.classList.remove("view-mode-switch__button--selected")
    );

    this.isMultiMode = e.currentTarget.dataset.mode === "multi";

    this.isCleared = false;
    this.clearButton.textContent = "clear all";

    this.updateUI();
  };

  // Syncs the visual state of mode buttons and ratio buttons with the current mode.
  // In multi mode, all ratios are selected; in single mode, only the first is.
  syncButtonStates = () => {
    this.modeOptions.forEach((modeOption) => {
      modeOption.classList.toggle(
        "view-mode-switch__button--selected",
        (this.isMultiMode && modeOption.dataset.mode === "multi") ||
          (!this.isMultiMode && modeOption.dataset.mode === "single")
      );
    });

    this.buttons.forEach((button, i) => {
      const isFirstButton = i === 0;
      this.isMultiMode
        ? button.classList.add("ratio-list__button--selected")
        : button.classList.toggle(
            "ratio-list__button--selected",
            isFirstButton
          );
    });
  };

  // Handles clicking on a ratio button. In multi mode, toggles selection;
  // in single mode, ensures only one ratio is selected at a time.
  // Always resets the clear state and updates the board.
  toggleButtonSelection = (e) => {
    if (this.isMultiMode) {
      e.currentTarget.classList.toggle("ratio-list__button--selected");
    } else {
      this.buttons.forEach((button) =>
        button.classList.remove("ratio-list__button--selected")
      );
      e.currentTarget.classList.add("ratio-list__button--selected");
    }

    this.isCleared = false;
    this.clearButton.textContent = "clear all";

    this.updateBoard();
  };

  getSizes = () => {
    return Array.from(this.buttons)
      .filter((button) =>
        button.classList.contains("ratio-list__button--selected")
      )
      .map((button) => ({
        width: button.dataset.width,
        height: button.dataset.height,
      }));
  };

  // Updates the preview board by showing only the banners that match
  // the currently selected sizes and hiding all others.
  updateBoard = () => {
    const servedSizes = this.getSizes(this.buttons);

    this.banners.forEach((banner) => {
      const iframe = banner.querySelector("iframe");
      const iframeWidth = iframe.width;
      const iframeHeight = iframe.height;

      const isMatch = servedSizes.some(
        (size) => size.width === iframeWidth && size.height === iframeHeight
      );

      banner.style.display = isMatch ? "block" : "none";
    });
  };

  // Toggles between clearing all size selections and selecting them all again.
  // Updates button states, changes the toggle button label, and refreshes the board.
  handleClearSelection = () => {
    this.isCleared = !this.isCleared;

    if (this.isCleared) {
      this.buttons.forEach((button) =>
        button.classList.remove("ratio-list__button--selected")
      );
      this.clearButton.textContent = "Select All";
    } else {
      this.buttons.forEach((button) =>
        button.classList.add("ratio-list__button--selected")
      );
      this.clearButton.textContent = "Clear All";
    }

    this.updateBoard();
  };
}

// Initialization function
export default function initModeManager() {
  const viewModeSwitch = document.querySelector(".view-mode-switch");
  const bannerSizeList = document.querySelector(".ratio-list");
  const clearButton = document.querySelector(".btn--toggler");
  const bannerPreviewBoard = document.querySelector("#board");

  new ModeManager(
    viewModeSwitch,
    bannerSizeList,
    bannerPreviewBoard,
    clearButton,
    {
      isMultiMode: true,
    }
  );
}
