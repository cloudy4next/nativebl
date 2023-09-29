import daterangepicker from "daterangepicker";
import Quill from "quill";

//daterangepicker
const test = new daterangepicker(document.getElementById("daterangepicker"));

document.addEventListener("DOMContentLoaded", () => {
    // AutoComplete


    const autocompleteInput = document.getElementById("autocomplete");
    const datalist = document.getElementById("autocomplete-datalist");

    const autocompleteData = [
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Grape",
        "Lemon",
        "Mango",
        "Orange",
        "Peach",
        "Pear",
    ];

    autocompleteInput.addEventListener("input", function () {
        const inputText = this.value.toLowerCase();
        const matchingItems = autocompleteData.filter((item) =>
            item.toLowerCase().includes(inputText)
        );

        datalist.innerHTML = "";

        if (matchingItems.length > 0) {
            matchingItems.forEach((item) => {
                const option = document.createElement("option");
                option.value = item;
                datalist.appendChild(option);
            });
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    // chain select
    const categorySelect = document.getElementById("category");
    const itemSelect = document.getElementById("chain-select");

    const itemsData = {
        fruits: ["Apple", "Banana", "Cherry"],
        vegetables: ["Carrot", "Lettuce", "Tomato"],
    };

    categorySelect.addEventListener("change", function () {
        const selectedCategory = this.value;
        const items = itemsData[selectedCategory];

        itemSelect.innerHTML = "";

        if (items) {
            items.forEach((item) => {
                const option = document.createElement("option");
                option.value = item;
                option.textContent = item;
                itemSelect.appendChild(option);
            });
        }
    });
});

//quill etidor

var toolbarOptions = [
    ["bold", "italic", "underline", "strike"], // toggled buttons
    ["blockquote", "code-block"],

    [{ header: 1 }, { header: 2 }], // custom button values
    [{ list: "ordered" }, { list: "bullet" }],
    [{ script: "sub" }, { script: "super" }], // superscript/subscript
    [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
    [{ direction: "rtl" }], // text direction

    [{ size: ["small", false, "large", "huge"] }], // custom dropdown
    [{ header: [1, 2, 3, 4, 5, 6, false] }],

    ["link", "image"],
    ["clean"], // remove formatting button
];

var quill = new Quill(document.getElementById("editor"), {
    modules: { toolbar: toolbarOptions },
    theme: "snow",
});

document.addEventListener("DOMContentLoaded", document);
