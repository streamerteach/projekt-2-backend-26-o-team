//dummy function to create profile cards for example purposes
export function createProfileContainer(amount) {
    const container = document.getElementById('datingProfilesContainer');
    for (let i = 0; i < amount; i++) {
        const profileCard = document.createElement('div');
        profileCard.className = 'profileCard';
        profileCard.innerText = `profile card example ${i + 1}`;
        container.appendChild(profileCard);
    }
}