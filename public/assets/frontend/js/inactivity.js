var idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 1000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
    $(this).touchstart(function (e) {
        idleTime = 0;
    });
    $(this).touchmove(function (e) {
        idleTime = 0;
    });
    $(this).touchend(function (e) {
        idleTime = 0;
    });
    $(this).touchcancel(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 60) { // 20 minutes
        window.location.href = "index.php?agence=<?php echo $ag;?>";
    }
}
