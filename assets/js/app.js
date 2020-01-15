/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
require('bootstrap/js/dist/tooltip');
require('bootstrap/js/dist/popover');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

const character = document.querySelector('#CharacterPosition');

function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
}

let hasItems = parseInt(getCookie("accessItem"), 10);

let UseItem = parseInt(getCookie("useItem"), 10);

let ObjectId = parseInt(getCookie("ObjectId"), 10);
let ObjectVictory = parseInt(getCookie("ObjectVictory"), 10);

let ObjectDesc = getCookie("ObjectDescription");
let ObjectImg = getCookie("ObjectImage");

console.log(hasItems);
console.log(UseItem);

console.log(ObjectId);
console.log(ObjectVictory);

console.log(ObjectDesc);
console.log(ObjectImg);

const SCALE = 0.8;
const WIDTH = 40;
const HEIGHT = 48;
const SCALED_WIDTH = SCALE * WIDTH;
const SCALED_HEIGHT = SCALE * HEIGHT;
const CYCLE_LOOP = [0, 1, 0, 2];
const FACING_DOWN = 0;
const FACING_UP = 1;
const FACING_LEFT = 2;
const FACING_RIGHT = 3;
const FRAME_LIMIT = 12;
const MOVEMENT_SPEED = 3;

let canvas = document.querySelector('canvas');
let ctx = canvas.getContext('2d');
let keyPresses = {};
let currentDirection = FACING_DOWN;
let currentLoopIndex = 0;
let frameCount = 0;
let positionX = parseInt(getCookie("positionX"), 10);
let positionY = parseInt(getCookie("positionY"), 10);
let img = new Image();

window.addEventListener('keydown', keyDownListener);
function keyDownListener(event) {
    keyPresses[event.key] = true;
}

window.addEventListener('keyup', keyUpListener);
function keyUpListener(event) {
    keyPresses[event.key] = false;
}

function loadImage() {
    img.src = 'https://i.postimg.cc/SRZ2Mtcg/test.png';
    img.onload = function() {
        window.requestAnimationFrame(gameLoop);
    };
}

function drawFrame(frameX, frameY, canvasX, canvasY) {
    ctx.drawImage(img,
        frameX * WIDTH, frameY * HEIGHT, WIDTH, HEIGHT,
        canvasX, canvasY, SCALED_WIDTH, SCALED_HEIGHT);
}

loadImage();

function gameLoop() {

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    let hasMoved = false;

    if (keyPresses.ArrowUp || keyPresses.w) {
        moveCharacter(0, -MOVEMENT_SPEED, FACING_UP);
        hasMoved = true;
    } else if (keyPresses.ArrowDown || keyPresses.s) {
        moveCharacter(0, MOVEMENT_SPEED, FACING_DOWN);
        hasMoved = true;
    }

    // Ghost District Zone
    let ghostImg = document.getElementById("GhostImage");
    let ghost = document.getElementById("GhostDistrictZone");

    if (positionX > 223 && positionX < 335 && positionY > 0 && positionY < 90) {
        ghost.style.display = 'inline';
        if (keyPresses.e) {
            ghostImg.style.display = 'table';
            ghost.innerHTML = "Ghost District <hr>" +
                ".... bUuuUUuh ...... bouUuuuh .... on est plus là ..... bouuuuUuUUUuuuuuUh ...";
            ghost.classList.add("character-dialog");
        }
        if (keyPresses.q) {
            ghostImg.style.display = 'none';
            ghost.style.display = 'none';
            ghost.innerHTML = "Bienvenue chez Ghost District!";
            ghost.classList.remove("character-dialog");
        }
    } else {
        ghostImg.style.display = 'none';
        ghost.style.display = 'none';
        ghost.innerHTML = "Bienvenue chez Ghost District!";
        ghost.classList.remove("character-dialog");
    }

    // White Pigeon Zone
    let pigeonImg = document.getElementById("PigeonImage");
    let pigeon = document.getElementById("WhitePigeonZone");

    if (positionX > 465 && positionX < 600 && positionY > 0 && positionY < 90) {
        pigeon.style.display = 'inline';
        if (keyPresses.e && hasItems !== 1) {
            pigeon.innerHTML = "Nous sommes fermé, revenez plus tard...";
        }
        if (keyPresses.e && hasItems === 1) {
            pigeonImg.style.display = 'table';
            document.getElementById("CharacterItems").style.display = 'table';
            pigeon.innerHTML = "White Pigeon <hr>" +
                "Rejoindre une équipe de pi..professionnels, dans une ambiance de me...magento.. Le stage rêvé de tous." +
                " Mais il vous faudra combattre cet adversaire pour obtenir ce stage, choisissez vite un item qui déterminera l'issue du combat... ou fuyez tant qu'il en est encore temps !";
            pigeon.classList.add("character-dialog");
        }
        if (keyPresses.q) {
            document.getElementById("CharacterItems").style.display = 'none';
            pigeon.innerHTML = "Bienvenue chez White Pigeon!";
            pigeonImg.style.display = 'none';
            pigeon.style.display = 'none';
            pigeon.classList.remove("character-dialog");
        }
    } else {
        document.getElementById("CharacterItems").style.display = 'none';
        pigeon.innerHTML = "Bienvenue chez White Pigeon!";
        pigeonImg.style.display = 'none';
        pigeon.style.display = 'none';
        pigeon.classList.remove("character-dialog");
    }

    // Chalet Zone
    let chaletImg = document.getElementById("ChaletImage");
    let chalet = document.getElementById("ChaletZone");

    if (positionX > 780 && positionX < 920 && positionY > 0 && positionY < 90) {
        chalet.style.display = 'table';
        if (hasItems === 1) {
            chalet.style.width = '220px';
            chalet.style.left = '708px';
            chalet.style.top = '5px';
        }
        if (keyPresses.e && hasItems === 1) {
            chalet.innerHTML = "Oust.. nécrophile!";
        }
        if (keyPresses.e && hasItems !== 1) {
            chaletImg.style.display = 'table';
            chalet.innerHTML = "Vous arrivez sur les lieux, une odeur nauséabonde se dégage du chalet." +
                " Vous passez la porte et découvrez avec horreur les cadavres nus de vos copains." +
                " Vous ne saurez certainement jamais ce qu'il s'est produit ici, car tout ce qu'il se passe au chalet, reste au chalet." +
                " Vous ramassez tout de même quelques objets, vous pourriez les utiliser ou au pire, les garder comme souvenir.";
            chalet.classList.add("character-dialog");
        }
        if (keyPresses.r && hasItems !== 1 && chaletImg.style.display === 'table') {
            window.location.href='/game/chalet';
        }
        if (keyPresses.q && hasItems !== 1) {
            document.getElementById("ChaletZone").style.display = 'none';
            if (hasItems === 1) {
                chalet.innerHTML = "Bon maintenant faut nettoyer tous ces cadavres...";
            } else {
            chalet.innerHTML = "Bienvenue au Chalet!";
            }
            chaletImg.style.display = 'none';
            chalet.classList.remove("character-dialog");
        }
    } else {
        document.getElementById("ChaletZone").style.display = 'none';
        if (hasItems === 1) {
            chalet.style.width = '220px';
            chalet.innerHTML = "Bon maintenant faut nettoyer tous ces cadavres...";
        } else {
            chalet.innerHTML = "Bienvenue au Chalet!";
        }
        chaletImg.style.display = 'none';
        chalet.classList.remove("character-dialog");
    }

    // Wild Code School Zone
    if (keyPresses.e && positionX > 315 && positionX < 520 && positionY > 285 && positionY < 360) {
            window.location.href='/';
    }

    // Think Fap Zone
    let fapImg = document.getElementById("FapImage");
    let fap = document.getElementById("ThinkFapZone");

    if (positionX > 725 && positionX < 910 && positionY > 225 && positionY < 350) {
        fap.style.display = 'table';
        if (keyPresses.e && hasItems !== 1) {
            fap.style.width = '220px'; fap.style.top = '250px'; fap.style.left = '697px';
            fap.innerHTML = "Désolé je ne suis pas d'humeur... si seulement j'avais une chicha...";
        }
        if (keyPresses.e && hasItems === 1) {
            fapImg.style.display = 'table';
            document.getElementById("CharacterItems").style.display = 'table';
            fap.innerHTML = "Think Fap <hr>" +
                "Donne moi du Think ! Donne moi du Fap ! (euh..) C'est la fabrique à Faprice !" +
                " Mais il vous faudra combattre cet adversaire pour obtenir ce stage, choisissez vite un item qui déterminera l'issue du combat... ou fuyez tant qu'il en est encore temps !";
            fap.classList.add("character-dialog");
        }
        if (keyPresses.q) {
            document.getElementById("CharacterItems").style.display = 'none';
            fap.innerHTML = "Bienvenue chez Think Fap!";
            fapImg.style.display = 'none';
            fap.style.display = 'none';
            fap.classList.remove("character-dialog");
        }
    } else {
        fapImg.style.display = 'none';
        fap.style.display = 'none';
        document.getElementById("ThinkFapZone").style.display = 'none';
        fap.innerHTML = "Bienvenue chez Think Fap!";
        fap.classList.remove("character-dialog");
    }

    let itemsGet = parseInt(getCookie("itemsGet"), 10);
    let infoBlock = document.getElementById("ItemsReceived");

    if (itemsGet === 1) {
        infoBlock.style.display = 'table';
    } else {
        infoBlock.style.display = 'none';
    }

    if (keyPresses.p) {
        console.log('Position H: ' + positionX);
        console.log('Position V: ' + positionY);
    }

    if (keyPresses.ArrowLeft || keyPresses.a) {
        moveCharacter(-MOVEMENT_SPEED, 0, FACING_LEFT);
        hasMoved = true;
    } else if (keyPresses.ArrowRight || keyPresses.d) {
        moveCharacter(MOVEMENT_SPEED, 0, FACING_RIGHT);
        hasMoved = true;
    }

    if (hasMoved) {
        frameCount++;
        if (frameCount >= FRAME_LIMIT) {
            frameCount = 0;
            currentLoopIndex++;
            if (currentLoopIndex >= CYCLE_LOOP.length) {
                currentLoopIndex = 0;
            }
        }
    }

    if (!hasMoved) {
        currentLoopIndex = 0;
    }

    drawFrame(CYCLE_LOOP[currentLoopIndex], currentDirection, positionX, positionY);
    window.requestAnimationFrame(gameLoop);
}

function moveCharacter(deltaX, deltaY, direction) {
    if (positionX + deltaX > 0 && positionX + SCALED_WIDTH + deltaX < canvas.width) {
        positionX += deltaX;
    }
    if (positionY + deltaY > 0 && positionY + SCALED_HEIGHT + deltaY < canvas.height) {
        positionY += deltaY;
    }
    currentDirection = direction;
}
