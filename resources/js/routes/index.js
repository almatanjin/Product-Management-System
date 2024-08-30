import { createWebHistory, createRouter } from 'vue-router'


const router = createRouter({
    history: createWebHistory(),
    routes:[
        {
            path: '/',
            component:()=>import("@/pages/Home.vue")
        },
        {
            path: '/create-product',
            component:()=>import("@/pages/CreateProduct.vue")
        },
        {
            path: '/edit-product/:id',
            component:()=>import("@/pages/EditProduct.vue")
        },
        {
            path: '/view-product/:id',
            component:()=>import("@/pages/ViewProduct.vue")
        },
        {
            path: '/product',
            component:()=>import("@/pages/Product.vue")
        },
        {
            path: '/category',
            component:()=>import("@/pages/Category.vue")
        },
    ]
})


export default router;
