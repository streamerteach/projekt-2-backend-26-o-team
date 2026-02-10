const input = document.getElementById('dateTimeInput');
const output = document.getElementById('output');

let countdown = null;

const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];



function formatDateFinnish(date) {
    const dayOfWeek = days[date.getDay()];
    const dayOfMonth = date.getDate();
    const month = months[date.getMonth()];
    return `${dayOfWeek} ${dayOfMonth}. ${month}`;
}

function updateCountdown(targetTimestamp) {
    const now = Date.now();
    const remaining = targetTimestamp - now;

    if (remaining <= 0) {
        output.classList.add('time-output');
        output.innerHTML = '<div><strong>The time has arrived!</strong></div>';
        clearInterval(countdown);
        return;
    }

    const totalSeconds = Math.floor(remaining / 1000);
    const days = Math.floor(totalSeconds / (24 * 3600));
    const hours = Math.floor((totalSeconds % (24 * 3600)) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    const targetDate = new Date(targetTimestamp);
    const formattedDate = formatDateFinnish(targetDate);

    output.classList.add('time-output');
    output.innerHTML = `
        <div>
            <div class="time-output-day"><strong>${formattedDate}</strong></div>
            <div class="time-output-countdown">
                ${days}D ${hours}H ${minutes}M ${seconds}S
            </div>
        </div>
    `;
}

function handleCalculate(event) {
    //saar do not reload the site. do not reload
    event.preventDefault();

    if (!input.value) {
        output.classList.add('time-output-error');
        output.innerHTML = '<div>please select a date and time.</div>';
        return;
    }

    const inputDate = new Date(input.value);
    const now = new Date();

    // chigitty chigitty check for past date
    if (inputDate < now) {
        output.classList.add('time-output-error');
        output.innerHTML = '<div>the selected date is in the past!</div>';
        return;
    }

    // get browser timezone offset in minutes
    const timezoneOffset = new Date().getTimezoneOffset();

    //im URI nating all over the place
    fetch("../scripts/timeToDate.php?dateTimeInput=" + encodeURIComponent(input.value) + "&tzOffset=" + timezoneOffset, {method: "GET"})

        //http to jason
        .then(response => response.json())
        .then(data => {
            const targetTimestamp = data.timestamp;

            // clear any existing interval
            if (countdown) {
                clearInterval(countdown);
            }

            // update immediately
            updateCountdown(targetTimestamp);

            // update every second
            countdown = setInterval(() => {
                updateCountdown(targetTimestamp);
            }, 1000);
        })
        .catch(error => {
            console.error('Error:', error);
            output.classList.add('time-output-error');
            output.innerHTML = '<div>Error calculating countdown</div>';
        });
}

// get form and attach submit listener
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', handleCalculate);
}

