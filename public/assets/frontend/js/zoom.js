//variable qui calculera la distance parcourus par les doigts, sur l'écran
var initialDistance1 = initialDistance2 = 0;
//si on touche l'écran avec plus d'un doigts, cette variable passe à true
//nous permet d'arrêter le zoom si ya plus d'un doigt posé
var isPinching = false;
// on écoute l'événement touchstart (on pose un ou plusieurs doigts sur l'écran)
document.addEventListener("touchstart", function(event){

    // si le nombre de doigts est plus d'un (le swipe ne sera pas pris en compte, on pourra toujours swiper)
    if(event.touches.length > 1){

        // si deux doigts touchent l'écran, on enregistre la distance initiale entre eux afin de savoir si ya un pincement ou un écartement
        initialDistance1 = getDistance(event.touches[0], event.touches[1]);

        // par sécurité nous prenons aussi un éventuel 3ème doigts
        initialDistance2 = initialDistance1;
        if(event.touches.length > 2){
            initialDistance2 = getDistance(event.touches[0], event.touches[2])
        }

        isPinching = true;

    }

    // "passive : false" pour prendre en compte les preventDefault() (peut être enlevé ici car il ny a pas d'utilisation de preventDefault(), mais par souci de propagation potentielle à l'événement touchmove, on va le laisser)
},{passive:false});
// on écoute l'événement touchmove (quand les doigts bougent sur l'écran)
document.addEventListener("touchmove", function(event){

    //si isPinching (donc 2 doigts sur l'écran, CF : touchstart event)
    //on s'assure qu'il y ait plus d'un doigt posé sur l'écran
    if(isPinching && event.touches.length > 1){

        // si deux doigts sont toujours présents, on calcule la nouvelle distance entre eux, s'ils s'écartent ou se rejoignent, on bloquera l'action


        var currentDistance1 = getDistance(event.touches[0],event.touches[1]);

        //si on a plus de 2 doigts sur l'écran, bloquer aussi
        var currentDistance2 = currentDistance1;
        if(event.touches.length > 2){
            currentDistance2 = getDistance(event.touches[0],event.touches[2])
        }
        // on compare la nouvelle distance avec la distance initiale pour déterminer si ya pincement ou écartement
        if(currentDistance1 > initialDistance1 || currentDistance2 > initialDistance2){

            event.preventDefault();

        }else if(currentDistance1 < initialDistance1 || currentDistance2 < initialDistance2){

            event.preventDefault();

        }
    }

    // "passive : false" pour prendre en compte les preventDefault() et stopper le zoom ou dézoom
},{passive:false});
// on écoute l'événement touchend afin de réinitialiser les distances
document.addEventListener("touchend", function(event){
    initialDistance1 = initialDistance2 = 0;
    isPinching = false;
});
// fonction pour calculer la distance entre deux points dans un plan bidimensionnel (entre deux doigts sur l'écran)
// Math.sqrt calcule la racine carrée pour retourner la distance
function getDistance(touch1, touch2){
    var dx = touch1.clientX - touch2.clientX;
    var dy = touch1.clientY - touch2.clientY;
    return Math.sqrt(dx * dx + dy * dy);
}
