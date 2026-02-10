import { CreateProfileCards } from "./scripts/CreateProfileCard.js";

window.onload = function () {
    CreateProfileCards(10);
};

//debug
window.CreateProfileCards = CreateProfileCards;