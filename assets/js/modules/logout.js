export default function logout() {
  const logoutButton =
    document.querySelector(".btn--logout") ||
    document.querySelector(".logout");

  if (!logoutButton) return;

  logoutButton.addEventListener("click", () => {
    window.location.href = "/logout";
  });
}
