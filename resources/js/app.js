require('./bootstrap');

import Alpine from 'alpinejs'

window.Alpine = Alpine

let toggleButtons = document.querySelectorAll('[data-toggle="toggle"]');
toggleButtons.forEach((element, index, original) => {
    element.addEventListener('click', event => {
        let span = element.querySelector('[aria-hidden="true"]');
        span.classList.toggle('translate-x-0');
        span.classList.toggle('translate-x-5');
        span.classList.toggle('bg-orange-500');
        span.classList.toggle('dark:bg-orange-500');
        span.classList.toggle('bg-white');
        span.classList.toggle('dark:bg-slate-500');
    });
});

document.querySelectorAll('[data-dark]').forEach((element, index, original) => {
    element.addEventListener('click', event => {
        if (localStorage.theme === 'dark') {
            localStorage.theme = 'light';
            document.documentElement.classList.remove('dark');
        } else {
            localStorage.theme = 'dark';
            document.documentElement.classList.add('dark');
        }
    });
});

if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.querySelectorAll('[data-toggle="toggle"]').forEach(element => {
        let span = element.querySelector('[aria-hidden="true"]');
        if (span.classList.contains('translate-x-0')) {
            span.classList.remove('translate-x-0');
            span.classList.add('translate-x-5');
            span.classList.add('bg-orange-500');
            span.classList.add('dark:bg-orange-500');
            span.classList.remove('bg-white');
            span.classList.remove('dark:bg-slate-500');
        }
    });
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

document.querySelectorAll('button').forEach(element => {
    element.addEventListener('mousedown', event => {
        event.preventDefault();
    });
});
Alpine.start()
