import initModeManager from "./mode-manager";

// Manages the dropdown UI logic for a custom select component
class CustomSelect {
  static currentOpenDropdown = null; // Tracks the currently open dropdown (only one open at a time)

  constructor(el) {
    this.root = el;
    this.header = el.firstElementChild;
    this.dropdown = el.lastElementChild;
    this.currentSelectedOption = this.dropdown.querySelector(
      ".custom-select__option--selected"
    );
    this.init();
  }

  // Sets up the custom select component by attaching two main event listeners:
  // 1) on the header button to toggle the dropdown open/closed,
  // 2) on the dropdown itself to handle option selection, update the header,
  // close the menu, and if the selection changed, fetch new dashboard data.
  init = () => {
    // Toggle dropdown visibility on header click
    this.header.addEventListener("click", (event) => {
      event.stopPropagation();
      event.preventDefault();
      this.toggleDropdown();
    });

    // Handle click on dropdown option (use closest to support inner spans/svg)
    this.dropdown.addEventListener("click", async (event) => {
      const option = event.target.closest(".custom-select__option");
      if (!option || !this.dropdown.contains(option)) return;

      const isAlreadySelected = option.classList.contains(
        "custom-select__option--selected"
      );

      this.editSelectedOption(option);
      this.closeDropdown(CustomSelect.currentOpenDropdown);

      if (isAlreadySelected) return;

      const currentSelection = this.captureCurrentSelection(option);
      const encoded = encodeURIComponent(JSON.stringify(currentSelection));

      try {
        const response = await fetch(`/dashboard-update?defaults=${encoded}`);
        const data = await response.json();

        this.replaceDashboardContent(data);
        this.reinitDynamicUi();
      } catch (err) {
        console.error("Failed to update dashboard:", err);
      }
    });
  };

  // Replaces the selectors, ratios, and board HTML if present.
  replaceDashboardContent = (data) => {
    const selectors = document.querySelector(".selectors");
    const ratios = document.querySelector(".ratio-list");
    const board = document.querySelector("#board");
    if (selectors) selectors.innerHTML = data.selectors;
    if (ratios) ratios.innerHTML = data.ratios;
    if (board) board.innerHTML = data.banners;
  };

  // Reinitializes components after DOM replacement so interactions keep working.
  reinitDynamicUi = () => {
    initCustomSelect();
    initModeManager();
  };

  // Collects the current selection state from all dropdowns. It builds an object
  // with the selected key for each dropdown type, updates it with the option just
  // clicked, and also includes the brand identifier from the dashboard root.
  captureCurrentSelection = (option) => {
    const selectorsWrapper = option.closest(".selectors");
    const dropdowns = selectorsWrapper.querySelectorAll(
      ".custom-select__dropdown"
    );

    const selectedKeys = {};

    dropdowns.forEach((dropdown) => {
      const type = dropdown.dataset.type;
      const selected = dropdown.querySelector(
        ".custom-select__option--selected"
      );

      if (selected && type) {
        selectedKeys[type] = selected.dataset.key;
      }
    });

    const clickedType = option.closest(".custom-select").dataset.type;
    const clickedKey = option.dataset.key;

    if (clickedType && clickedKey) {
      selectedKeys[clickedType] = clickedKey;
    }

    const rootElement = document.querySelector("#dashboard");
    if (rootElement) {
      selectedKeys.brand = rootElement.dataset.brand;
    }

    return selectedKeys;
  };

  // Toggles the current dropdown between open and closed states.
  // If another dropdown is already open, it closes it first to ensure only one dropdown remains open at a time.
  toggleDropdown = () => {
    if (
      CustomSelect.currentOpenDropdown &&
      CustomSelect.currentOpenDropdown !== this.dropdown
    ) {
      this.closeDropdown(CustomSelect.currentOpenDropdown);
    }

    const isVisible = this.dropdown.style.display === "block";
    isVisible
      ? this.closeDropdown(this.dropdown)
      : this.openDropdown(this.dropdown);
  };

  // Detects clicks outside the currently open dropdown and closes it when necessary.
  // Ensures only interactions inside the dropdown keep it open.
  handleOutsideClick = (event) => {
    if (
      CustomSelect.currentOpenDropdown &&
      !CustomSelect.currentOpenDropdown.parentElement.contains(event.target)
    ) {
      this.closeDropdown(CustomSelect.currentOpenDropdown);
    }
  };

  // Closes the given dropdown by hiding it, removing active classes,
  // resetting the caret rotation, and unbinding the outside click listener.
  closeDropdown(dropdown) {
    if (!dropdown) return;

    dropdown.style.display = "none";
    const parent = dropdown.closest(".custom-select");
    parent.classList.remove("is-open");

    const caretSvg = parent.querySelector("svg");
    caretSvg.style.transform = "rotate(0deg)";
    if (caretSvg) caretSvg.style.transform = "rotate(0deg)";

    CustomSelect.currentOpenDropdown = null;
    document.removeEventListener("click", this.handleOutsideClick);
  }

  // Opens the given dropdown by making it visible, adding active classes,
  // rotating the caret, and binding the outside click listener.
  openDropdown = (dropdown) => {
    if (!dropdown) return;

    dropdown.style.display = "block";
    const parent = dropdown.closest(".custom-select");
    parent.classList.add("is-open");

    const caretSvg = parent.querySelector("svg");
    if (caretSvg) caretSvg.style.transform = "rotate(180deg)";

    CustomSelect.currentOpenDropdown = dropdown;
    document.addEventListener("click", this.handleOutsideClick);
  };

  // Updates the selected option in the dropdown. It removes the active class
  // from the previously selected option, applies it to the new one, stores it
  // as the current selection, and updates the header title with the option’s label.
  editSelectedOption = (newSelectedOption) => {
    if (this.currentSelectedOption) {
      this.currentSelectedOption.classList.remove(
        "custom-select__option--selected"
      );
    }

    newSelectedOption.classList.add("custom-select__option--selected");
    this.currentSelectedOption = newSelectedOption;

    const labelText = newSelectedOption.querySelector(
      ".custom-select__label"
    )?.textContent;
    const titleSpan = this.header.querySelector(".custom-select__title");
    if (titleSpan) titleSpan.textContent = labelText;
  };
}

// Initializes all dropdowns elements on the page
const initCustomSelect = () => {
  const defaultSelects = document.querySelectorAll(".custom-select");
  defaultSelects.forEach((select) => new CustomSelect(select));
};

export default initCustomSelect;
