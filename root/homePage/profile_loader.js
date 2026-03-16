let chunk = 1;
let loading = false;
let hasMore = true;
// filter values
let preference = 0; // All as default
let minlikes = 0;
let match = 1;

let profilescontainer = document.getElementById("datingProfilesContainer"); 
let loadingindicator = document.getElementById('loadingIndicator');
let endmessage = document.getElementById('endMessage');
let errormessage = document.getElementById('errorMessage');
let prefFilter = document.getElementById('pref');
let likeFilter = document.getElementById('likes');
let matchwithyou = document.getElementById('match');

const mapPref = ['All', 'Men', 'Women', 'Other'];
const mapGender = ['Man', 'Woman', 'Other'];

document.addEventListener('DOMContentLoaded', () => {
    loadProfiles();
});

async function loadProfiles() {
    if (loading || !hasMore) return;
    loading = true;
    loadingindicator.classList.add('active');
    errormessage.classList.remove('active');
    try {
        let message = `./profileshandler.php?chunk=${chunk}&l=${minlikes}&p=${preference}`;
        if (match) {
            message += `&m=1`;
        }
 
        const response = await fetch(message);
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log(response);
        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        displayProfiles(data.profiles);
        
        hasMore = data.hasMore;
        chunk++;
        
        // Show end message if no more profiles
        if (!hasMore) {
            endmessage.classList.add('active');
        }
    } catch (error) {
        console.error('Error fetching profiles:', error);
        errormessage.classList.add('active');
    } finally {
        loading = false;
        loadingindicator.classList.remove('active');
    }
}


function displayProfiles(profiles) {
    profiles.forEach(profile => {
        const profileCard = createProfileCard(profile);
        profilescontainer.appendChild(profileCard);
    });
}

function createProfileCard(profile) {
    const card = document.createElement('a');
    card.className = 'profile-card';
    card.href = `../user/viewuser.php?user=${profile.username}`;

    const displayName = profile.realname || profile.username;
    const isSoftbanned = (Number(profile.is_softbanned) === 1);

    if (isSoftbanned) {
        card.classList.add('softbanned-profile');
    }

    card.innerHTML = `
        <div class="profile-header">
            <img class="profile-avatar" src="../media/upload/${profile.username}_profile.jpg" onerror="this.onerror=null; this.src='../media/Default.jpg'">
            <div class="profile-info">
                <h3>${displayName} ${isSoftbanned ? '<span class="banned-tag">[Softbanned]</span>' : ''}</h3>
            </div>
        </div>
        <div class="profile-details">
            <p><strong></strong> ${mapGender[profile.gender] || 'Not specified'}</p>
            <p><strong>${profile.bio || 'No bio provided'}</strong></p>
            <p><strong>Location:</strong> ${profile.zipcode || 'Not specified'}</p>
            <p><strong>Preference:</strong> ${mapPref[profile.preference] || 'Not specified'}</p>
            <p>${profile.email || 'Not specified'}</p>
            <p><strong>Likes:</strong> ${profile.likes || 0}</p>
            <div class="salary">${profile.salary_formatted || 'Salary not specified'}</div>
        </div>
    `;

    if (window.currentUserRole >= 3) {
        const actionBtn = document.createElement('button');
        actionBtn.textContent = isSoftbanned ? 'Un-softban' : 'Softban';
        actionBtn.className = 'softban-toggle-btn';
        actionBtn.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();
            actionBtn.disabled = true;
            try {
                const result = await toggleSoftban(profile.id, profile.username);
                if (result && typeof result.is_softbanned !== 'undefined') {
                    const newState = Number(result.is_softbanned) === 1;
                    if (newState) {
                        card.classList.add('softbanned-profile');
                        actionBtn.textContent = 'Un-softban';
                    } else {
                        card.classList.remove('softbanned-profile');
                        actionBtn.textContent = 'Softban';
                    }
                }
            } catch (err) {
                console.error('Softban toggle failed', err);
                alert('Failed to update softban status.');
            } finally {
                actionBtn.disabled = false;
            }
        });

        const placeholder = document.createElement('div');
        placeholder.className = 'softban-button-container';
        placeholder.appendChild(actionBtn);
        card.appendChild(placeholder);
    }

    return card;
}

async function toggleSoftban(profileId, profileUsername) {
    const response = await fetch('../scripts/profile_softban.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ profile_id: profileId, profile_username: profileUsername }),
    });

    if (!response.ok) {
        const errPayload = await response.json().catch(() => null);
        throw new Error(errPayload?.error || 'Softban request failed');
    }

    return await response.json();
}
// Retry loading on error
function retryLoading() {
    errormessage.classList.remove('active');
    loadProfiles();
}


let timeout;

const debounce = (func, delay) => { // prevent too many calls
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
};

const bottomload = debounce(() => {
    const pixels = 250; // pixels from bottom
    const scrollPosition = window.innerHeight + window.scrollY;
    const documentHeight = document.documentElement.scrollHeight;
    if (documentHeight - scrollPosition <= pixels) {
        loadProfiles();
    } else {
        console.log(documentHeight - scrollPosition)
    }

}, 500);

window.addEventListener('scroll', bottomload);


function reloadProfiles() {
    
    console.log('JE::PP');
    if (loading == true) {
        return;
    }
    endmessage.classList.remove('active');
    errormessage.classList.remove('active');

    preference = prefFilter.value;
    minlikes = likeFilter.value;
    match = matchwithyou.checked == true ? 1 : 0;

    chunk = 1;
    hasMore = true;
    profilescontainer.innerHTML = null;

    loadProfiles();
}