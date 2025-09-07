export default function logout() {
  document.querySelector(".btn--log.out").addEventListener("click", () => {
    window.location.href = "/logout";
  });
}
