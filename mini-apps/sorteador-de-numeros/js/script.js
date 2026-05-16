const form = document.querySelector("form");
const inputNumbers = document.querySelectorAll("input[regex-number]");
const listResult = [];
var rodadaDoSorteio = 0;
const rodadaDoSorteioSpan = document.getElementById('lottery-number');

const resultSection = document.getElementById('resultado');
const resultSectionList = document.querySelector('#resultado .list-results');

// REGEX para para os inputs de número
inputNumbers.forEach((inp) => {
    inp.addEventListener("input", (e)=>{
        // Regex para manter somente números
        const numbersRegex = /[^\d]/g;
        e.target.value = e.target.value.replace(numbersRegex, "");
    })
})

form.onsubmit = (e) => {
    e.preventDefault();

    listResult.splice(0, listResult.length);
    resultSectionList.innerHTML = "";

    const qntNumbers = parseInt(document.getElementById("qnt-numbers").value);
    const minNumbers = parseInt(document.getElementById("min-numbers").value);
    const maxNumbers = parseInt(document.getElementById("max-numbers").value);
    const dontRepeatNumbers = document.getElementById("checkbox-reapeat").checked;

    if((maxNumbers - minNumbers + 1) < qntNumbers && dontRepeatNumbers == true){
        document.getElementById("checkbox-reapeat").checked = false;
        return;
    }

    for(let rep = 0; rep < qntNumbers; rep++){
        let newNumber = sortearNumbero(minNumbers, maxNumbers);
        
        if(dontRepeatNumbers == true){
            if(listResult.includes(newNumber) == true){
                rep--;
                continue;
            }
        }
        
        listResult.push(newNumber);
        
        const numberSpan = document.createElement('span');
        numberSpan.classList.add('result', 'overline');
        numberSpan.innerText = newNumber;
        resultSectionList.append(numberSpan);
    }

    rodadaDoSorteio++;
    rodadaDoSorteioSpan.innerText = rodadaDoSorteio;

    form.classList.add('display-none');
    resultSection.classList.remove('display-none');
    
};

function sortearNumbero(min, max){
    let newNumber = Math.floor(Math.random() * (max - min + 1)) + min;
    return newNumber;
}

function sortearNovamente(){
    form.classList.remove('display-none');
    resultSection.classList.add('display-none');
}