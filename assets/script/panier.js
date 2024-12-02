const formNumber = document.getElementById('formNumber')
const nombre = document.querySelectorAll('.nombre')
const envoyer = document.querySelectorAll('.envoyer')

if (nombre) {
     const nombresInitiaux = []
     nombre.forEach((element, index) => {
          // Stocker la valeur initiale de chaque champ
          nombresInitiaux.push(element.value)

          // Ajouter un gestionnaire d'événement à chaque champ
          element.addEventListener('input', (e) => {
               // Comparer la valeur actuelle avec la valeur initiale
               if (e.target.value == nombresInitiaux[index]) {
                    envoyer[index].disabled = true
               } else {
                    envoyer[index].disabled = false
               }
          })
     })
}
