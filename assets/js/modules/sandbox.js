import { Pane } from "tweakpane";

export default function initSandbox() {
  const PARAMS = {
    width: 300,     // Initial width in pixels
    height: 250,    // Initial height in pixels
    color: true,    // ✅ Checked by default
    crop: "contain" // Default background-size
  };

  const pane = new Pane();

  // Width (integer steps)
  pane.addBinding(PARAMS, "width", {
    min: 120,
    max: 1000,
    step: 1          // ✅ integer only
  }).on("change", (ev) => {
    const banner = document.getElementById("banner");
    if (banner) banner.style.width = `${ev.value}px`;
  });

  // Height (integer steps)
  pane.addBinding(PARAMS, "height", {
    min: 100,
    max: 600,
    step: 1          // ✅ integer only
  }).on("change", (ev) => {
    const banner = document.getElementById("banner");
    if (banner) banner.style.height = `${ev.value}px`;
  });

  // Color (checked by default)
  pane.addBinding(PARAMS, "color").on("change", (ev) => {
    const imageHolders = document.querySelectorAll(".image-holder");
    imageHolders.forEach((holder) => {
      holder.style.backgroundColor = ev.value ? "red" : "transparent";
      holder.style.border = ev.value ? "1px solid red" : "transparent";
    });
  });

  // Crop
  pane.addBinding(PARAMS, "crop", {
    options: { Contain: "contain", Cover: "cover" }
  }).on("change", (ev) => {
    const imageHolders = document.querySelectorAll(".image-holder");
    imageHolders.forEach((holder) => {
      holder.style.backgroundSize = ev.value;
    });
  });

  // Initial apply
  const banner = document.getElementById("banner");
  if (banner) {
    banner.style.width = `${PARAMS.width}px`;
    banner.style.height = `${PARAMS.height}px`;
  }
  // Apply initial color state
  const imageHolders = document.querySelectorAll(".image-holder");
  imageHolders.forEach((holder) => {
    holder.style.backgroundColor = PARAMS.color ? "red" : "transparent";
    holder.style.border = PARAMS.color ? "1px solid red" : "transparent";
  });
}
