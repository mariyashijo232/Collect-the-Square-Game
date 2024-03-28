<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Collect The Square Game: </title>

  <style>
    canvas {
      border: 1px solid #000;
    }
  </style>
</head>
<body>
  <div style="display: flex;">
    <canvas id="canvas" width="600" height="400" style="border: 1px solid #000;" tabindex="0"></canvas>
    <div id="scoreboard" style="margin-left: 20px;">
      <h2>Scoreboard</h2>
      <table border="1">
        <tr>
          <th>Player</th>
          <th>Score</th>
        </tr>
      </table>
    </div>
  </div>
  <script>
    var obstacleX = 0;
    var obstacleY = 0;
    var obstacleSize = 30;
    var obstacleSpeed = 3;

    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');

    var score = 0;
    var x = 50;
    var y = 100;
    var speed = 6;
    var sideLength = 50;
    var down = false;
    var up = false;
    var right = false;
    var left = false;
    var targetX = 0;
    var targetY = 0;
    var targetLength = 25;

    function isWithin(a, b, c) {
      return a > b && a < c;
    }

    var countdown = 30;
    var id = null;

    window.addEventListener('keydown', function(event) {
      event.preventDefault();
      if (event.keyCode === 40) { down = true; }
      if (event.keyCode === 38) { up = true; }
      if (event.keyCode === 37) { left = true; }
      if (event.keyCode === 39) { right = true; }
    });

    window.addEventListener('keyup', function(event) {
      event.preventDefault();
      if (event.keyCode === 40) { down = false; }
      if (event.keyCode === 38) { up = false; }
      if (event.keyCode === 37) { left = false; }
      if (event.keyCode === 39) { right = false; }
    });

    function menu() {
      erase();
      context.fillStyle = '#000000';
      context.font = '36px Arial';
      context.textAlign = 'center';
      context.fillText('Collect the Square!', canvas.width / 2, canvas.height / 4);
      context.fillText('Make sure to avoid the blue square!', canvas.width / 2, canvas.height / 3);
      context.font = '24px Times New Roman';
      context.fillText('Click to Start', canvas.width / 2, canvas.height / 2);
      context.font = '18px Arial';
      context.fillText('Use the arrow keys to move', canvas.width / 2, (canvas.height / 4) * 3);
      canvas.addEventListener('click', startGame);
    }

    function startGame() {
  // Get the username from the user before starting the game
  var username = prompt('Enter your username:');
  if (!username) {
    // Handle the case where the user cancels the prompt
    return;
  }

  id = setInterval(function() {
    countdown--;
  }, 1000);

  canvas.removeEventListener('click', startGame);
  moveTarget();
  draw();
}


    function submitScoreToServer() {
      console.log('Score submitted to the server');
    }

    function endGame() {
  clearInterval(id);
  context.font = '18px Arial';
  context.fillText('Game Over! Your Score: ' + score, canvas.width / 2, (canvas.height / 4) * 3);
  context.fillText('Click to Restart', canvas.width / 2, (canvas.height / 4) * 3 + 30);

  // Listen for click on the document
  document.addEventListener('click', restartGameOnClick);

  // Call submitScoreToServer here (only once)
  submitScoreToServer();
}

function restartGameOnClick() {
  document.removeEventListener('click', restartGameOnClick);
  restartGame();
}

function restartGame() {
  document.removeEventListener('click', restartGameOnClick);
  startGame();
  erase();
}

function submitScoreToServer() {
      var xhr = new XMLHttpRequest();
      
      var submitScoreURL = 'submit_score.php';

      var data = 'username=' + encodeURIComponent(username) + '&score=' + encodeURIComponent(score);

      xhr.open('POST', submitScoreURL, true);

      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log(xhr.responseText); 
        }
      };

      xhr.send(data);
    }

    function moveTarget() {
      targetX = Math.round(Math.random() * canvas.width - targetLength);
      targetY = Math.round(Math.random() * canvas.height - targetLength);
    }

    function erase() {
      context.fillStyle = '#FFFFFF';
      context.fillRect(0, 0, 600, 400);
    }

    function moveObstacle() {
      obstacleX += obstacleSpeed;
      if (obstacleX > canvas.width) {
        obstacleX = 0 - obstacleSize;
        obstacleY = Math.round(Math.random() * canvas.height - obstacleSize);
      }
    }

    function checkObstacleCollision() {
      if (
        x < obstacleX + obstacleSize &&
        x + sideLength > obstacleX &&
        y < obstacleY + obstacleSize &&
        y + sideLength > obstacleY
      ) {
        endGame();
      }
    }

    function drawObstacle() {
      context.fillStyle = '#0037FF';
      context.fillRect(obstacleX, obstacleY, obstacleSize, obstacleSize);
    }

    function draw() {
      erase();
      moveObstacle();
      checkObstacleCollision();
      drawObstacle();

      if (down) { y += speed; }
      if (up) { y -= speed; }
      if (right) { x += speed; }
      if (left) { x -= speed; }
      if (y + sideLength > canvas.height) { y = canvas.height - sideLength; }
      if (y < 0) { y = 0; }
      if (x < 0) { x = 0; }
      if (x + sideLength > canvas.width) { x = canvas.width - sideLength; }

      if (
        x < obstacleX + obstacleSize &&
        x + sideLength > obstacleX &&
        y < obstacleY + obstacleSize &&
        y + sideLength > obstacleY
      ) {
        endGame();
        return;
      }

      if (isWithin(targetX, x, x + sideLength) && isWithin(targetY, y, y + sideLength)) {
        moveTarget();
        score++;
      }

      context.fillStyle = '#FF0000';
      context.fillRect(x, y, sideLength, sideLength);
      context.fillStyle = '#00FF00';
      context.fillRect(targetX, targetY, targetLength, targetLength);
      context.fillStyle = '#000000';
      context.font = '24px Arial';
      context.textAlign = 'left';
      context.fillText('Score: ' + score, 10, 24);
      context.fillText('Time Remaining: ' + countdown, 10, 50);

      if (countdown <= 0) {
        endGame();
      } else {
        window.requestAnimationFrame(draw);
      }
    }

    menu();
    canvas.focus();
  </script>
</body>
</html>
