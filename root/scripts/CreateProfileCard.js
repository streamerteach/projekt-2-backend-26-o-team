export function CreateProfileCards(profileAmount) {
    const container = document.getElementById('datingProfilesContainer');
    for (let i = 0; i < profileAmount; i++) {
        const profileCard = document.createElement('div');
        profileCard.className = 'profileCard';
        profileCard.innerText = `Profile Card Example ${i + 1}`;
        container.appendChild(profileCard);
    }
}