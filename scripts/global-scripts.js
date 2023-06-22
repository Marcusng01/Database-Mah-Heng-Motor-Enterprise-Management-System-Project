//Header opacity change on scroll logic
const header = document.querySelector('.header');
window.addEventListener('scroll', (e)=>{
    if(window.pageYOffset==0)
        header.classList.remove("translucent");
    else
        header.classList.add("translucent");
})
