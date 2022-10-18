/**
 * @property {HTMLElement} content
 * @property {HTMLFormElement} form
 */
export default class Filter {

  /**
   * 
   * @param {HTMLElement|null} element 
   */
  constructor (element) {
    if(element === null) {
      return
    }
    this.content = element.querySelector('.js-filter-content')
    this.form = element.querySelector('.js-filter-form')
    this.bindEvents()
  }
  /**
  *  content = utilisateurs qui sont filtrés
  *  form = mon formulaire _filter.html.twig
  */

  /**
   * bindEvents() ajoute les comportements aux différents élements
   */
  bindEvents() {
    this.form.querySelectorAll('input').forEach(input => {
      input.addEventListener('input', this.loadForm.bind(this)) // Lance la fonction loadForm()
    }) // Écoute toutes les entrées qui ont lieu dans un input 
  }

  async loadForm() { // Fonction qui génère automatiquement l'url à partir des données du formulaire
    const data = new FormData(this.form) // Je récupère les données à partir du formulaire
    const url = new URL(this.form.getAttribute('action') || window.location.href)
    // l' url récupère les actions du form, sinon l'url courante.
    const params = new URLSearchParams()
    // je génère les paramètres d'url dynamiquement avec l'objet UrlSearchParams()
    data.forEach((value, key) => { // Je parcours l'ensemble des données de mon form 
      params.append(key, value)
    })
    // et je les transvase aux params
    return this.loadUrl(url.pathname + '?' + params.toString())
  }

  async loadUrl(url) {    // Prend en paramètre une url à charger, et fait le traitement AJAX
    const response = await fetch(url, {
      headers : { // Ajoute un entête pour préciser que c'est une requête AJAX
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    if (response.status >=200 && response.status < 300) { // Si on a une requête qui a répondu convenablement
      const data = await response.json() // 'data' récupère les données sous format JSON
      this.content.innerHTML = data.content // 'content' va nous renvoyer les données pour récupérer les utilisateurs
    } else {
      console.error(response)
    }
  }
}