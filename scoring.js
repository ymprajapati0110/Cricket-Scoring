let totalRuns = 0;
let wickets = 0;
let balls = 0;
let overs = 0;
let currentOverBalls = 0;
let totalBalls = 0;
let batsmanOut = false;
let selectedBatsmen = [];
let firstInnings = true;
let firstInningsScore = 0;
let battingTeam, bowlingTeam; 

function loadPlayers(teamType) {
    const teamSelect = document.getElementById(teamType + 'Team');
    const teamName = teamSelect.value;
    const playerSelect = document.getElementById(teamType === 'batting' ? 'batsman' : 'bowler');

    if (teamName) {
        fetch(`fetch_players.php?team_name=${encodeURIComponent(teamName)}`)
            .then(response => response.json())
            .then(players => {
                const availableBatsmen = players.filter(player => !selectedBatsmen.includes(player));
                playerSelect.innerHTML = '<option value="">Select Player</option>';
                availableBatsmen.forEach(player => {
                    const option = document.createElement('option');
                    option.value = player;
                    option.textContent = player;
                    playerSelect.appendChild(option);
                });

                if (availableBatsmen.length === 0) {
                    updateCommentary("No more batsmen available.");
                }
            })
            .catch(error => console.error('Error fetching players:', error));
    } else {
        playerSelect.innerHTML = '<option value="">Select Player</option>';
    }
}

function updateScoreboard() {
    document.getElementById('totalScore').textContent = `${totalRuns}/${wickets}`;
    document.getElementById('overs').textContent = `${overs}.${currentOverBalls}`;
}

function handleScoring(runs, isWide = false, isNoBall = false) {
    if (isWide || isNoBall) {
        totalRuns += 1; 
        updateCommentary(isWide ? "Wide ball" : "No ball");
    } else {
        totalRuns += runs;
        currentOverBalls += 1;
        totalBalls += 1;
        updateCommentary(`${runs} run(s) scored`);
    }

    updateScoreboard();

    if (currentOverBalls >= 6) {
        overs += 1;
        currentOverBalls = 0;
        loadPlayers('bowling'); 
        updateCommentary("End of the over. Select a new bowler.");
    }
}

function updateCommentary(message) {
    document.getElementById('commentaryBox').textContent = message;
}

document.querySelectorAll('.button-grid button').forEach((button, index) => {
    button.addEventListener('click', () => {
        switch (index) {
            case 0: handleScoring(0); break; 
            case 1: handleScoring(1); break; 
            case 2: handleScoring(2); break; 
            case 3: handleScoring(3); break; 
            case 4: handleScoring(4); break; 
            case 5: handleScoring(6); break; 
            case 6: handleScoring(1, true); break; 
            case 7: handleScoring(1, false, true); break; 
            case 8: handleScoring(1); break; 
            case 9: handleScoring(1); break; 
            case 10: handleWicket(); break; 
        }
    });
});

function handleWicket() {
    wickets += 1;
    currentOverBalls += 1;
    totalBalls += 1;
    batsmanOut = true;
    updateCommentary("Wicket falls! Select a new batsman.");

    loadPlayers('batting');
    updateScoreboard();
}

document.getElementById('batsman').addEventListener('change', (event) => {
    const selectedBatsman = event.target.value;

    if (selectedBatsman && !selectedBatsmen.includes(selectedBatsman)) {
        selectedBatsmen.push(selectedBatsman);
        updateCommentary(`${selectedBatsman} is now batting.`);
    }
});

function swapTeams() {
    const battingTeamSelect = document.getElementById('battingTeam');
    const bowlingTeamSelect = document.getElementById('bowlingTeam');

    const temp = battingTeamSelect.value;
    battingTeamSelect.value = bowlingTeamSelect.value;
    bowlingTeamSelect.value = temp;

    battingTeamSelect.disabled = true;
    bowlingTeamSelect.disabled = true;

    loadPlayers('batting');  
    loadPlayers('bowling');  
}

function resetForSecondInnings() {
    firstInnings = false;
    firstInningsScore = totalRuns;

    totalRuns = 0;
    wickets = 0;
    balls = 0;
    overs = 0;
    currentOverBalls = 0;
    totalBalls = 0;
    batsmanOut = false;
    selectedBatsmen = [];

    updateCommentary("Innings ended. Resetting for the second innings.");
    
    swapTeams(); 

    document.getElementById('batsman').innerHTML = '<option value="">Select Player</option>';
    document.getElementById('bowler').innerHTML = '<option value="">Select Player</option>';

    loadPlayers('batting'); 
    loadPlayers('bowling');  

    updateScoreboard();
}

function endInnings() {
    if (firstInnings) {
        resetForSecondInnings();
    } else {
        if (totalRuns > firstInningsScore) {
            updateCommentary("Second team wins! They chased the target successfully.");
        } else if (totalRuns === firstInningsScore) {
            updateCommentary("It's a tie! Both teams scored the same runs.");
        } else {
            updateCommentary("First team wins! The second team failed to chase the target.");
        }

        document.querySelectorAll('.button-grid button').forEach(button => button.disabled = true);
    }
}

document.getElementById('endInningsBtn').addEventListener('click', endInnings);
