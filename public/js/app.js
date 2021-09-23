function onClickBtnLike(event){
    event.preventDefault();
    const url = this.href;
    const span = this.querySelector('span.js-likes');
    const icon = this.querySelector('i');
    const card_body = document.querySelector('div.card-body');

    axios.get(url).then(function(response){
        span.textContent = response.data.Likes;
        if(icon.classList.contains('fa-thumbs-up')){
            icon.classList.replace('fa-thumbs-up', 'fa-thumbs-o-up')
        } else {
            icon.classList.replace('fa-thumbs-o-up', 'fa-thumbs-up')
        }

    }).catch(function(error){
        if(error.response.status === 403){
            const alert = document.createElement('span');
            card_body.appendChild(alert);
            alert.textContent = "You must be registered/ logged in to post a like";
            alert.classList.toggle("text-warning");
            alert.classList.add("container-fluid");

        }
    });
}

document.querySelectorAll('a.js-like').forEach(function(link){
    link.addEventListener('click', onClickBtnLike)
})