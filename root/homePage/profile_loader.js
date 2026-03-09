let chunk = 1;
let loading = false;
let hasMore = true;
let profilescontainer = document.getElementById("datingProfilesContainer"); 
let loadingindicator = document.getElementById('loadingIndicator');
let endmessage = document.getElementById('endMessage');
let errormessage = document.getElementById('errorMessage');
const map = ['All', 'Men', 'Women', 'Other'];

document.addEventListener('DOMContentLoaded', () => {
    loadProfiles();
});

async function loadProfiles() {
    if (loading || !hasMore) return;
    loading = true;
    loadingindicator.classList.add('active');
    errormessage.classList.remove('active');
    try {
        const response = await fetch(`./profileshandler.php?chunk=${chunk}`);
        
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
    time = 2000; // 2000 = 2 seconds
    waittime = time / profiles.length;
    profiles.forEach(profile => {
        const profileCard = createProfileCard(profile);
        profilescontainer.appendChild(profileCard);
        
    });
}

function createProfileCard(profile) {
    const card = document.createElement('a');
    card.className = 'profile-card';
    card.href = `../user/viewuser.php?user=${profile.username}`;
    
    // Map database fields to display fields
    const displayName = profile.realname;
    

    
    card.innerHTML = `
        <div class="profile-header">
            <img class="profile-avatar" src="../media/upload/${profile.username}_profile.jpg" onerror="this.onerror=null; this.src='../media/Default.jpg'">
            <div class="profile-info">
                <h3>${displayName}</h3>
            </div>
        </div>
        <div class="profile-details">
        <p><strong> ${profile.bio || 'No bio provided'}</strong></p>
            <p><strong>Location:</strong> ${profile.zipcode || 'Not specified'}</p>
            <p><strong>Preference:</strong> ${map[profile.preference] || 'Not specified'}</p>
            <p>${profile.email || 'Not specified'}</p>
            <p><strong>Likes:</strong> ${profile.likes || 0}</p>
            <div class="salary">${profile.salary_formatted || 'Salary not specified'}</div>

        </div>
    `;
    
    return card;
}
// Retry loading on error
function retryLoading() {
    errormessage.classList.remove('active');
    loadProfiles();
}
// Optional: Reset and reload all profiles
function resetAndReload() {
    profilescontainer.innerHTML = '';
    chunk = 1;
    hasMore = true;
    endmessage.classList.remove('active');
    errormessage.classList.remove('active');
    loadProfiles();
}

let timeout;

const debounce = (func, delay) => {
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