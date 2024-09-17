"use strict";

let dog = "le chien"; 
console.log(dog);

let demo = document.querySelector("#demo");
//console.log(demo);
 let content = demo.innerHTML;
//console.log(content);
// console.log(typeof content);
document.querySelector("#demo").addEventListener("mouseover", function () {
demo.innerHTML = new Date();
});

document.querySelector("#demo").addEventListener("mouseout", function () {
demo.innerHTML = content;
});

//function myFunctionToggler() {
   // element.style.background = " blue "
//}

//console.log("coucou du fichier install_js.js");
//------ exemple d'utilisation Boucle FOR-----
//variable pour cibler la class .para dans la page
//let para= document.querySelectorAll(".para");
//console.log renvoie un tableau
//console.log(para);

/*for (let i = 0; i < para.length; i++) {
    // change la couleur du texte en rouge
    para[i].style.color="";  
}

let selectBody = document.getElementsByTagName("body");
console.log(selectBody);
selectBody[0].style.background = "white";

let selectBtn = document.getElementsByTagName("button");
console.log(selectBtn);

//selectBtn[3].style.background = "orange";
//selectBtn[4].style.background = "orange";

//let favoriteColor = window.prompt(" What is your  favorite color?");
//console.log(favoriteColor); */


//NB :  myElement.innerHTML = ici ça attend une string; dc lorsqu'on variabilise ca put être une string (..un objet)

/*function myFunction2() {
    //console.log("demo");
    for (let i = 0; i < demo.length; i++){}
    document.getElementById("demo").innerHTML = new Date();
    document.getElementById("demo").addEventListener("mouseover", function () {
        document.getElementById("demo").innerHTML = `<button class="fs-1" id="demo" 
        onmouseover="myFunction2()">Date et heure</button>`
       
   }); 
  
}*/

//console.log(document.querySelectorAll("#donner"));


/*function myFunction3(event) {
    console.log("event", event);
    console.log("X :", event.clientX);
    console.log("Y", event.clientY);
    //console.log("dblclick");
    document.querySelectorAll("#donner")[0].style.color = "red";
    console.log("my text is : " + document.querySelectorAll("#donner")[0].innerHTML);
        
} */

// méthode avec la focntion anonyme function();
/*document.querySelectorAll("#donner")[0].ondblclick = function() {
   // console.log("dblclick");
    this.style.color = "blue";
    console.log("  my text is :" +this.innerHTML); 
    
    ou encore :

     function dblClick(){
         console.log("dblclick");
         this.style.color = "blue";
        console.log("  my text is :" +this.innerHTML); 
     }

     document.querySelectorAll("#donner")[0].ondblclick = dblClick;

}*/
// NB: chaque objet HTML hérite de addEventListener().
// NB: myFunction est une stand alone function cad qui ne vient d'aucune classe mais est présente dans le script.
// l'objet Event est un petit l'objet parent de tous les évènements en JS, et ns donne les infos complémentaires sur l'objet en cours. l'objet Event un peu spécial parce qu'on l'hérite au moment de capter l'évènement.


// focus : lorsqu'on appuie dans un input.
//document.querySelectorAll("#input[type='text']")[1].addEventListener("change", function () {
    //console.log("change ");
    
//});
//console.log(document.querySelector("#inputEmail"));
// REQUETE ASYBCHRONE :communiquer avec le serveur sans recharger la page.

 
    // CALL BACK FUNCTION (HOVER)

    /**-------------Deuxième méthode----------------
console.log(document.querySelectorAll("#img"));
let img = document.querySelectorAll("#img");
//console.log(img.length);
for (let i = 0; i < img.length; i++) {
    img[i].addEventListener("mouseover", function () {
        this.style.width = "90px";
        
    });
}

for (let i = 0; i < img.length; i++){
    img[i].addEventListener("mouseout", function () {
        this.style.width = "";
        
    });
}
 */
// Méthode : est une fontion qui se trouve à l'intérieur d'une classe ou un objet. 
  
//NB : pour récuperer le texte d'un élement HTML en  JS c'est innerHTML.

//   -----------------------------------JQUERY -----------------------------


 


 //document.querySelectorAll("#navbar-toggler")[0].ondblclick = function() {
   //console.log("dblclick");
  // this.style.color = "blue";
  // console.log("  my text is :" +this.innerHTML);
    
   // ou encore :

    // function dblClick(){
        // console.log("dblclick");
       //  this.style.color = "blue";
       // console.log("  my text is :" +this.innerHTML);
     //}

   //



 //la méthode .map()en JS parcours les éléments d'un tableau. et chaque fois récupere un elt et nous renvoie un nouvel array.
 // (e ou event) =>{ } : e ou event donne les informations complémentaires sur l'objet.
 // === compare la valeur et le type de valeur.
 