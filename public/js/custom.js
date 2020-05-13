/**
 *
 * @param {Product} product
 * @returns {*}
 */
function productBadge(product) {
    let $mainWrap = $('<div>', {'class': 'product-container', 'data-product-id': product.id}).append(
        $('<img/>', {
            'class': 'thumbnail-img',
            'src': `${product.url}`,
            'alt': 'thumbnail image'
        })
    );

    let $secondWrap = $('<div>', {'class': 'product-info-wrapper'});
    let $productWrap = $('<div>', {'class': 'product-title', text: product.name});
    let $additionalInfoWrap = $('<div>', {'class': 'additional-info'});
    let $productPrice = $('<span>', {'class': 'product-price', text: product.price + 'Eur | '});
    let $categoryName = $('<span>', {'class': 'product-category', text: product.category.name});
    let $productActionsWrap = $('<div>', {
        class: 'actions-wrapper'
    }).append($('<div>', {
        'class': 'edit-product',
        'data-product-id': product.id,
        text: 'Edit product'
    })).append($('<div>', {
        'class': 'delete-product',
        'data-product-id': product.id,
        text: 'Delete product'
    }));

    let $threeDotsWrap = $('<div>', {'class': 'img-wrapper'})
        .append($('<img/>', {
            'class': 'edit-product-img',
            'src': assetBaseUrl + "images/three-dots.png",
            'alt': 'Edit product'
        })).append($productActionsWrap);

    $additionalInfoWrap.append($productPrice);
    $additionalInfoWrap.append($categoryName);
    $secondWrap.append($productWrap);
    $secondWrap.append($additionalInfoWrap);
    $mainWrap.append($secondWrap);
    $mainWrap.append($threeDotsWrap);

    return $mainWrap;
}

/**
 *
 * @param {Product} product
 * @returns {*}
 */
function clientViewProductBadge(product) {
    let $mainWrap = $('<article>', {
        'class': 'product-miniature col-lg-4 col-md-6 col-sm-6 col-xs-12',
        'data-product-id': product.id
    });
    let $innerWrap = $('<div>', {
        'class': 'product-container-vertical'
    });

    let $imgContainer = $('<a>', {
        'href': '/products/' + product.id
    }).append($('<div>', {
        'class': 'product-image'
    }).append($('<img>', {
        'class': 'p-image',
        'src': `${product.url}`,
        'data-src': `${product.url}`,
        'alt': 'product image'
    })));

    let $productInfo = $('<div>', {
        'class': 'product-info',
        'text': product.name
    }).append($('h5', {
        'class': 'product-title'
    })).append($('<div>', {
        'class': 'product-price',
        'text': product.price + ' Eur'
    }));

    let $btnForm = $('<div>', {
        'class': 'product-badge-footer'
    }).append($('<input>', {
        'type': 'button',
        'id': 'buy-btn',
        'class': 'btn btn-primary',
        'value': 'Add to Cart'
    }));

    $innerWrap.append($imgContainer);
    $innerWrap.append($productInfo);
    $innerWrap.append($btnForm);
    $mainWrap.append($innerWrap);

    return $mainWrap;
}

/**
 *
 * @param {CartProduct} cartProducts
 * @returns {*}
 */
function cartProductsViewBadge(cartProducts) {
    let $mainWrapper = $('<div>', {
        "class": "cart-container row",
        'data-product-id': cartProducts.product.id,
        'data-cart-id': cartProducts.cart.id
    });

    let $imgContainer = $('<div>', {
        'class': 'img-container col-sm-2'
    }).append($('<img>', {
        'class': 'product-image-miniature',
        'alt': 'product image',
        'src': `${cartProducts.product.url}`
    }));

    let $quantityContainer = $('<div>', {
        'class': 'col-sm-4'
    }).append($('<label>', {
        'for': 'product-quantity',
        'text': 'Quantity: '
    })).append($('<input>', {
        'class': 'product-quantity',
        'type': 'number',
        'value': cartProducts.quantity
    }));

    $mainWrapper
        .append($imgContainer)
        .append($('<div>', {
            'class': 'product-name col-sm-2',
            'text': cartProducts.product.name
        }))
        .append($quantityContainer)
        .append($('<div>', {
            'class': 'spinner-border',
            'role': 'status'
        })
            .append($('<span>', {
                'class': 'sr-only',
                'text': 'Loading...'
            })))
        .append($('<div>', {
            'class': 'product-price col-sm-3',
            'text': 'Price:'
        })
            .append($('<span>', {
                'class': 'pPrice',
                'text': cartProducts.product.price * cartProducts.quantity
            }))
            .append($('<span>', {
                'text': 'Eur'
            }))
            .append($('<div>', {
                'class': 'remove-from-cart',
                'text': 'Remove'
            })));

    return $mainWrapper;
}

/**
 *
 * @param {Product} product
 * @returns {*}
 */
function singleProductBadge(product) {
    let mainContainer = $('<article>', {
        'class': 'product-article',
        'data-category-id': product.category.id,
        'data-product-id': product.id
    });

    let innerContainer = $('<div>', {
        'class': 'single-product-container'
    });

    let imgContainer = $('<div>', {
        'class': 'product-image'
    }).append($('<img>', {
        'class': 'single-product-img',
        'src': `${product.url}`,
        'data-src': `${product.url}`,
        'alt': 'product image'
    }));

    let infoContainer = $('<div>', {
        'class': 'product-info'
    }).append($('<div>', {
        'class': 'product-title',
        'text': product.name
    })).append($('<div>', {
        'class': 'product-description',
        'text': typeof product.description !== "undefined" ? product.description : 'There is no product description!'
    }));

    let $btnForm = $('<div>', {
        'id': 'buy_product',
    }).append($('<div>', {
        'class': 'product-price',
        'text': 'Price: ' + product.price + ' Eur'
    })).append($('<label>', {
        'for': 'product_quantity',
        'text': 'Quantity:',
    })).append($('<input>', {
        'type': 'number',
        'id': 'product_quantity',
        'value': '1'
    })).append($('<button>', {
        'type': 'submit',
        'data-product-id': product.id,
        'class': 'btn btn-primary',
        'text': 'Add to Cart'
    }));

    innerContainer.append(imgContainer);
    innerContainer.append(infoContainer);
    mainContainer.append(innerContainer);
    mainContainer.append($btnForm);

    return mainContainer;
}

function showCartProductsQuantity() {
    $.ajax({
        url: ajaxCartProductsQuantity,
        method: 'POST',
        data: {'_token': csrfToken},
        success: function (response) {
            $('#my_cart').find('span').first().text(response['quantity']);
        },
        error: function (err) {
            ajaxCompleted = true;
            console.log(err.responseText);
        }
    });
}

function addToCartPopUp(product, quantity) {
    if (quantity <= 0) {
        quantity = 1;
    }

    let editForm = `
                    <div class="cart-container row">
                        <div class="img-container col-sm-2">
                            <img src="${product.img_url}" class="product-image-miniature" alt="product image">
                        </div>
                        <div class="product-name col-sm-2">${product.name}</div>
                        <div class="col-sm-4">Quantity: ${quantity}</div>
                        <div class="product-price col-sm-3">Total price: ${quantity * product.price} Eur</div>
                    </div>
            `;

    bootbox.dialog({
        title: 'The product was added to the cart.',
        message: editForm,
        size: 'large',
        onShown: function () {
            cartProductsQuantity();
        },
        buttons: {
            Cancel: {
                label: "Cancel",
                className: 'btn-danger',
                callback: function () {
                    console.log('Custom cancel clicked');
                }
            },
            Confirm: {
                label: "Look at the cart",
                className: 'btn-info',
                callback: function () {
                    window.location.href = "/cart/products";
                }
            }
        }
    })
}

function cartProductsQuantity() {
    $.ajax({
        url: ajaxQuantityProductsCart,
        method: 'POST',
        data: {
            '_token': csrfToken
        },
        success: function (response) {
            $('#my_cart').find('span').first().text(response['quantity']);
        },
        error: function (err) {
            ajaxCompleted = true;
            console.log(err.responseText);
        }
    });
}

class Product {

    constructor() {
        this.status = false;
    }

    get id() {
        return this._id;
    }

    set id(val) {
        if (val == null) {
            throw new Error("Id must be indicated");
        }

        this._id = val;
    }

    get name() {
        return this._name;
    }

    set name(val) {
        this._name = val;
    }

    get created() {
        return this._created;
    }

    set created(val) {
        this._created = val;
    }

    get description() {
        return this._description
    }

    set description(val) {
        this._description = val;
    }

    get price() {
        return this._price;
    }

    set price(val) {
        this._price = val;
    }

    get category() {
        return this._category;
    }

    set category(val) {
        if (!(val instanceof Category)) {
            return new Error("This is not category object!");
        }

        this._category = val;
    }

    get status() {
        return this._isEdited;
    }

    set status(val) {
        this._isEdited = val;
    }

    get url() {
        return this._url;
    }

    set url(val) {
        this._url = val;
    }

    static getNameId() {
        return "id";
    }

    static getNameProduct() {
        return "name";
    }

    static getNameDescription() {
        return "description";
    }

    static getNameCreated() {
        return "created";
    }

    static getNamePrice() {
        return "price";
    }

    static getNameImgUrl() {
        return 'img_url';
    }

    static getNameStatus() {
        return "status";
    }

    static bindProductObject(obj) {
        let product = new Product();

        product.category = Category.bindCategoryObject(obj);
        product.id = typeof obj[Product.getNameId()] !== undefined ? obj[Product.getNameId()] : null;
        product.name = typeof obj[Product.getNameProduct()] !== undefined ? obj[Product.getNameProduct()] : null;
        product.created = typeof obj[Product.getNameCreated()] !== undefined ? obj[Product.getNameCreated()] : null;
        product.description = typeof obj[Product.getNameDescription()] !== undefined ? obj[Product.getNameDescription()] : null;
        product.price = typeof obj[Product.getNamePrice()] !== undefined ? obj[Product.getNamePrice()] : null;
        product.url = typeof obj[Product.getNameImgUrl()] !== undefined ? obj[Product.getNameImgUrl()] : null;

        return product;
    }

    edit() {
        let data = {};
        data.action = "edit_product";
        data.id = this._id;
        data.name = this._name;
        data.price = this._price;
        data.category_id = this._category.id;

        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            dataType: 'Json',
            data: data
        });
    }

    static getProduct(productId) {
        return $.ajax({
            url: ajaxGetProductRoute,
            type: 'POST',
            dataType: 'Json',
            data: {'id': productId, '_token': csrfToken}
        });
    }
}

class Category {
    get id() {
        return this._id;
    }

    set id(val) {
        if (val == null) {
            throw new Error("Id must be indicated");
        }

        this._id = val;
    }

    get name() {
        return this._name;
    }

    set name(val) {
        this._name = val;
    }

    static getNameId() {
        return "category_id";
    }

    static getNameCategory() {
        return "category_name"
    }

    /**
     *
     * @param obj
     * @returns {Category}
     */
    static bindCategoryObject(obj) {
        let category = new Category();
        category.id = typeof obj[Category.getNameId()] !== undefined ? obj[Category.getNameId()] : null;
        category.name = typeof obj[Category.getNameCategory()] !== undefined ? obj[Category.getNameCategory()] : null;

        return category;
    }
}

class Cart {
    get id() {
        return this._id;
    }

    set id(val) {
        if (val == null) {
            throw new Error("Id must be indicated");
        }

        this._id = val;
    }

    get createdAt() {
        return this._createdAt;
    }

    set createdAt(val) {
        this._createdAt = val;
    }

    get updatedAt() {
        return this._updatedAt;
    }

    set updatedAt(val) {
        this._updatedAt = val;
    }

    static getNameId() {
        return 'id'
    }

    static getNameCreatedAt() {
        return 'created_at';
    }

    static getNameUpdatedAt() {
        return 'updated_at'
    }

    /**
     *
     * @param obj
     * @returns {Cart}
     */
    static bindCategoryObject(obj) {
        let cart = new Cart();
        cart.id = typeof obj[Cart.getNameId()] !== undefined ? obj[Cart.getNameId()] : null;
        cart.createdAt = typeof obj[Cart.getNameCreatedAt()] !== undefined ? obj[Cart.getNameCreatedAt()] : null;
        cart.updatedAt = typeof obj[Cart.getNameUpdatedAt()] !== undefined ? obj[Cart.getNameUpdatedAt()] : null;

        return cart;
    }
}

class CartProduct {
    get id() {
        return this._id;
    }

    set id(val) {
        this._id = val;
    }

    /**
     *
     * @returns {Cart}
     */
    get cart() {
        return this._cart;
    }

    /**
     *
     * @param {Cart} val
     */
    set cart(val) {
        this._cart = val;
    }

    /**
     *
     * @returns {Product}
     */
    get product() {
        return this._product;
    }

    set product(val) {
        this._product = val;
    }

    get quantity() {
        return this._quantity;
    }

    set quantity(val) {
        this._quantity = val;
    }

    get totalPrice() {
        return this._totalPrice;
    }

    set totalPrice(val) {
        this._totalPrice = val;
    }

    static getNameId() {
        return 'id'
    }

    static getNameQuantity() {
        return 'quantity';
    }

    static getNameTotalPrice() {
        return 'total_price';
    }

    static bindCartProductObject(obj) {
        let cartProduct = new CartProduct();
        cartProduct.id = typeof obj[CartProduct.getNameId()] !== undefined ? obj[CartProduct.getNameId()] : null;
        cartProduct.quantity = typeof obj[CartProduct.getNameQuantity()] !== undefined ? obj[CartProduct.getNameQuantity()] : null;
        cartProduct.totalPrice = typeof obj[CartProduct.getNameTotalPrice()] !== undefined ? obj[CartProduct.getNameTotalPrice()] : null;
        const productObject = typeof obj['product'] !== "undefined" ? obj['product'] : null;
        if (productObject !== null) {
            cartProduct.product = Product.bindProductObject(productObject);
        }

        const cartObject = typeof obj['cart'] !== "undefined" ? obj['cart'] : null;
        if (cartObject !== null) {
            cartProduct.cart = Cart.bindCategoryObject(cartObject);
        }

        return cartProduct;
    }

}