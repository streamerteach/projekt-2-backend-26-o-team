
addEventListener("DOMContentLoaded", () => {
document.getElementById('blueButton').addEventListener("click", () => {
    console.log("blue button clicked");
    document.getElementById('blueButton').classList.remove('buttonMove');
    document.getElementById('reportSection').classList.remove('proj2Section');
})});