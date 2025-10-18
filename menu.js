
const categories = [...new Set(menuData.map((item) => item.type))];

const renderMenu = (containerId = ".menu-grid", data) => {
  data.map((product, index) => {
    const container = document.createElement("div");
    container.className = "menu-item";
    container.setAttribute("data-aos", "fade-up");
    container.setAttribute("data-aos-delay", `${index * 100}`);

    const img = document.createElement("img");
    img.src = product.image;
    img.alt = product.name;
    container.appendChild(img);

    const itemContent = document.createElement("div");
    itemContent.className = "item-content";
    container.appendChild(itemContent);

    const itemTitle = document.createElement("h3");
    itemTitle.className = "item-title";
    itemTitle.textContent = product.name;
    itemContent.appendChild(itemTitle);

    const span = document.createElement("span");
    span.className = `type`;
    span.textContent = product.type;
    itemContent.appendChild(span);

    const price = document.createElement("div");
    price.className = "price";
    price.textContent = `P${product.price.toFixed(2)}`;
    itemContent.appendChild(price);

    const addButton = document.createElement("button");
    addButton.className = "add-button";
    addButton.textContent = "Add";
    addButton.onclick = () =>
      addToCart(product.id, product.name, product.price);
    itemContent.appendChild(addButton);

    document.getElementById(containerId).appendChild(container);
  });
};

const dessertSnackProducts = menuData.filter((item) => item.type === "Dessert/Snack");
renderMenu("dessert-snack-section", dessertSnackProducts);

const espressoProducts = menuData.filter((item) => item.type === "Espresso");
renderMenu("espresso-section", espressoProducts);

const nonCoffeeProducts = menuData.filter((item) => item.type === "Non-Coffee");
renderMenu("non-coffee-section", nonCoffeeProducts);

const fruitTeaProducts = menuData.filter((item) => item.type === "Fruit Tea");
renderMenu("fruit-tea-section", fruitTeaProducts);

// const lihamSetProducts = menuData.filter((item) => item.type === "Liham Set");
// renderMenu("liham-set-section", lihamSetProducts);

const addOnProducts = menuData.filter((item) => item.type === "Rice Meals");
renderMenu("rice-meals-section", addOnProducts);

const othersProducts = menuData.filter((item) => item.type === "Yakult Series");
renderMenu("yakult-series-section", othersProducts);

let cart = {};
let currentTip = 0;

function addToCart(id, name, price) {
  if (!cart[id]) {
    cart[id] = {
      name: name,
      price: price,
      quantity: 0,
    };
  }
  cart[id].quantity++;
  updateCartDisplay();
}

function removeFromCart(id) {
  if (cart[id] && cart[id].quantity > 0) {
    cart[id].quantity--;
    if (cart[id].quantity === 0) {
      delete cart[id];
    }
    updateCartDisplay();
  }
}

function setTip(percentage) {
  currentTip = percentage;
  document.querySelectorAll(".tip-button").forEach((button) => {
    button.classList.remove("selected");
  });
  if (percentage === "custom") {
    const customTip = prompt("Enter tip percentage:", "15");
    if (customTip !== null) {
      currentTip = parseInt(customTip) || 0;
    }
  }
  document
    .querySelector(
      `.tip-button:nth-child(${
        percentage === 0 ? 1 : percentage === 10 ? 2 : percentage === 20 ? 3 : 4
      })`
    )
    .classList.add("selected");
  updateCartDisplay();
}

function updateCartDisplay() {
  const cartItemsDiv = document.getElementById("cartItems");
  cartItemsDiv.innerHTML = "";
  let subtotal = 0;

  for (const [id, item] of Object.entries(cart)) {
    const itemTotal = item.price * item.quantity;
    subtotal += itemTotal;

    cartItemsDiv.innerHTML += `
            <div class="cart-item">
                <div>
                    <div>${item.quantity}x ${item.name}</div>
                    <div>$${itemTotal.toFixed(2)}</div>
                </div>
                <div class="quantity-controls">
                    <button class="quantity-button" onclick="removeFromCart('${id}')">−</button>
                    <button class="quantity-button" onclick="addToCart('${id}', '${
      item.name
    }', ${item.price})">+</button>
                </div>
            </div>
        `;
  }

  const tipAmount = subtotal * (currentTip / 100);
  const total = subtotal + tipAmount;
  document.getElementById("totalAmount").textContent = `$${total.toFixed(2)}`;
}

// Add active class to the current nav link based on scroll position
const sections = document.querySelectorAll("section");
const categoryLinks = document.querySelectorAll(".categories-link");

window.addEventListener("scroll", () => {
  let current = "";

  sections.forEach((section) => {
    const sectionTop = section.offsetTop + 700;
    const sectionHeight = section.clientHeight;
    if (pageYOffset >= sectionTop - sectionHeight / 3) {
      current = section.getAttribute("id");
    }
  });

  categoryLinks.forEach((link) => {
    link.classList.remove("active");
    if (link.getAttribute("href").substring(1) === current) {
      link.classList.add("active");
    }
  });
});

// CHECKOUT
const checkoutButton = document.querySelector(".checkout-button");
checkoutButton.addEventListener("click", () => {
  if (Object.entries(cart)?.length === 0) {
    return swal("Cart is empty!", "Please add items to your cart.", "error");
  }
  swal("Order Success!", "Thank you for your purchase!", "success");
  cart = {};
  updateCartDisplay();
});

addButton.onclick = (event) => {
  event.preventDefault(); // Add this to stop any default action
  addToCart(product.id, product.name, product.price);
};

const addButton = document.createElement("button");
addButton.className = "add-button";
addButton.textContent = "Add";
addButton.type = "button"; // This prevents form submission

