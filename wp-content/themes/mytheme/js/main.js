let elems = document.querySelectorAll(".slider__item");
let prev_link = document.querySelector(".slider__arrow_prev");
let next_link = document.querySelector(".slider__arrow_next");
let dots = document.querySelectorAll(".slider__dot");

let elem = Array.from(elems);
let dot = Array.from(dots);
window.i = 0;
  elem[i].className = "slider__item slider__item_active";
  dot[i].className = "slider__dot slider__dot_active";

dot.forEach((item, index) => {
  item.addEventListener("click", (e) => {
    console.log(index, e);
    if (index == e.target.dataset.value) {
      //i = e.target.dataset.value;
      item.className = "slider__dot slider__dot_active";
      elem[index].className = "slider__item slider__item_active";
      console.log(index);
    }
    for (let i = 0; i < dot.length; i++) {
      console.log("index= " + index);
      if (index != i) {
        dot[i].className = "slider__dot";
        elem[i].className = "slider__item";
        console.log("i= " + i);
      }
    }
    window.i = Number(e.target.dataset.value);
  });
});
console.log(window.i);

    let index = window.i;
prev_link.addEventListener("click", (e, index) =>{
 index = Number(window.i);
  elem[index].className = "slider__item";
  index = index - 1;
  if (index < 0) {
    index = elem.length-1;
  }
  elem[index].className = "slider__item slider__item_active";
    dot[index].className = "slider__dot slider__dot_active";
    window.i = index;
    dot.forEach((item, index) => {
      if (window.i != index) {
        item.className = "slider__dot";
      }
    });
})
console.log(window.i);

    let index2 = window.i;
next_link.addEventListener("click", (e, index2) =>{
    index2 = Number(window.i)  
  console.log(index2);
  elem[index2].className = "slider__item";
  index2 = index2 + 1;
   if (index2 >= elem.length) {
    index2 = 0;
  }
 console.log(index2);
  elem[index2].className = "slider__item slider__item_active";
    dot[index2].className = "slider__dot slider__dot_active";
     window.i = index2;
dot.forEach((item, index) => {
    if (window.i != index) {
        item.className = "slider__dot";
    }
})
 
})
