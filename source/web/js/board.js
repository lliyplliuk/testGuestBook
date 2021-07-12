const evenListeners = () => {
    document.querySelectorAll('.closeModal').forEach((item) => {
        item.addEventListener('click', hideModal)
    })
    document.getElementById('saveModal').addEventListener('click', saveModal)
    loadData()
}

document.addEventListener("DOMContentLoaded", evenListeners)

const loadData = () => {
    fetch('/ajax.php?action=get')
        .then(res => res.json())
        .then(response => {
            document.getElementById("main").innerHTML = ''
            document.getElementById("spinner").style.display = "none"
            response.messages.forEach(msg => {
                renderMsg(msg)
            })
            const button = document.createElement('button')
            button.classList.add('btn', 'btn-success')
            button.innerHTML = "Добавить сообщение"
            button.addEventListener('click', () => {
                openAdd(0)
            })
            document.getElementById("main").appendChild(button)
        })
        .catch(err => {
            console.log("u")
            alert("sorry, there are no results for your search")
        });
}

const saveModal = () => {
    hideModal()
    document.getElementById("main").innerHTML = ''
    document.getElementById("spinner").style.display = "block"
    const act = document.getElementById('mainModal').dataset.act
    fetch(`/ajax.php?action=${act}`, {
        method: 'POST',
        // headers: {
        //     'Content-Type': 'application/json'
        // },
        body: JSON.stringify({
            id: document.getElementById('mainModal').dataset.id,
            name: document.getElementById('msgName').value,
            text: document.getElementById('msgText').value,
        })
    })
        .then(res => res.json())
        .then((resource) => {
            loadData()
            if(resource.data===false)
                alert('Слишком часто пишете сообщения, попробуйте попробовать чуть позже.')
        })
}

const openAdd = (id = 0) => {
    document.getElementById('modalTitle').innerHTML = "Добавить сообщение"
    const myModal = document.getElementById('mainModal')
    myModal.dataset.id = id
    myModal.dataset.act = 'add'
    document.getElementById('msgName').value = ''
    document.getElementById('msgText').value = ''
    myModal.style.display = "block"
}

const openChange = (id, name, text) => {
    document.getElementById('modalTitle').innerHTML = "Добавить сообщение"
    const myModal = document.getElementById('mainModal')
    myModal.dataset.id = id
    myModal.dataset.act = 'change'
    document.getElementById('msgName').value = name
    document.getElementById('msgText').value = text
    myModal.style.display = "block"
}

const renderMsg = (msg, level = 0) => {
    const date = new Date(msg.timeCreated * 1000);
    const btnAnswer = document.createElement('button')
    btnAnswer.classList.add('btn', 'btn-success')
    btnAnswer.innerHTML = "Ответить"
    btnAnswer.addEventListener('click', () => {
        openAdd(msg.id)
    })
    const divMsg = document.createElement('div')
    divMsg.classList.add('col')
    divMsg.style.paddingLeft = `${level * 50}px`
    const divCard = document.createElement('div')
    divCard.classList.add('card', 'mb-4', 'rounded-3', 'shadow-sm')
    divCard.innerHTML = `<div class="card-header py-3">
    <h4 class="my-0 fw-normal">${msg.nameAuthor}<small class="text-muted">${date.toLocaleDateString()} ${date.toLocaleTimeString()}</small></h4>
    </div><div class="card-body"><p>${msg.text}</p></div>`
    divCard.appendChild(btnAnswer)
    if (msg.sesId === '1') {
        const btnChange = document.createElement('button')
        btnChange.classList.add('btn', 'btn-info')
        btnChange.innerHTML = "Изменить"
        btnChange.addEventListener('click', () => {
            openChange(msg.id, msg.nameAuthor, msg.text)
        })
        divCard.appendChild(btnChange)
    }
    divMsg.appendChild(divCard)
    document.getElementById("main").appendChild(divMsg)
    if (msg.answers !== undefined)
        msg.answers.forEach(msg => {
            renderMsg(msg, level + 1)
        });
}

const hideModal = () => {
    document.getElementById('mainModal').style.display = "none"
}