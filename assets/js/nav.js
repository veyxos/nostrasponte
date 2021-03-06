import { mediaLg } from "./responsive.js"

const menuButton = document.querySelector('.site-header__menu-button')
const darkener = document.querySelector('.site-header__navigation__darkener')

export const navToggle = () => {
  menuButton.addEventListener('click', () => {
    toggleMenu()
  })
  darkener.addEventListener('click', toggleMenu)
}

const toggleMenu = () => {
  if (menuButton.classList.contains('site-header__menu-button--closed')) {
    menuButton.classList.remove('site-header__menu-button--closed')
    menuButton.classList.add('site-header__menu-button--open')
    darkener.style.display = 'block'
    document.body.style.overflow = 'hidden'
  } else {
    menuButton.classList.remove('site-header__menu-button--open')
    menuButton.classList.add('site-header__menu-button--closed')
    darkener.style.display = 'none'
    document.body.style.overflow = 'initial'
  }
}

export function searchStayOpen () {
  const search = document.querySelector('.site-header__navigation__search')
  search.addEventListener('mouseover', function () {
    if (mediaLg()) this.classList.add('site-header__navigation__item--open')
    else if (this.classList.contains('site-header__navigation__item--open')) this.classList.remove('site-header__navigation__item--open')
  })
  search.addEventListener('mouseleave', function () {
    setTimeout(() => this.classList.remove('site-header__navigation__item--open'), 1500)
  })
}
