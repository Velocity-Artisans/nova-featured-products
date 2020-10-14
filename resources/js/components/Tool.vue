<template>
  <div>
    <heading class="mb-6">Featured Products</heading>

    <loading-view :loading="initialLoading">
      <template v-if="!loading && products.length">
        <div class="flex" style="">
          <div class="relative h-9 flex-no-shrink mb-6">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 20 20"
              aria-labelledby="search"
              role="presentation"
              class="fill-current absolute search-icon-center ml-3 text-70"
            >
              <path
                fill-rule="nonzero"
                d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"
              ></path>
            </svg>
            <input
              data-testid="search-input"
              dusk="search"
              placeholder="Search"
              type="search"
              class="appearance-none form-search w-search pl-search shadow"
              v-model="search"
            />
          </div>
          <div class="w-full flex items-center mb-6">
            <div class="flex w-full justify-end items-center mx-3"></div>
            <div class="flex-no-shrink ml-auto">
              <button
                type="button"
                class="btn btn-default btn-primary"
                dusk="save-button"
                @click="updateProducts"
              >
                Save
              </button>
            </div>
          </div>
        </div>
      </template>
      <loading-card :loading="loading" class="card">
        <div
          class="overflow-hidden overflow-x-auto relative"
          v-if="filteredProducts.length"
        >
          <table
            cellpadding="0"
            cellspacing="0"
            data-testid="resource-table"
            class="table w-full table-default"
          >
            <thead>
              <tr>
                <th class="text-left"><span>Title</span></th>
                <th class="text-center"><span>Price</span></th>
                <th class="text-center"><span>Available</span></th>
                <th class="text-center"><span>Featured</span></th>
              </tr>
            </thead>
            <tbody>
              <tr
                dusk="1-row"
                v-for="(product, index) in filteredProducts"
                :key="index"
              >
                <td>
                  <div class="text-left text-left">
                    <span
                      class="whitespace-no-wrap"
                      v-html="product.title"
                    ></span>
                    <span
                      class="block whitespace-no-wrap opacity-75 mt-1"
                      v-html="product.merchant.name"
                    ></span>
                  </div>
                </td>
                <td>
                  <div class="text-center text-center">
                    <span
                      class="whitespace-no-wrap"
                      v-html="product.price"
                    ></span>
                  </div>
                </td>
                <td>
                  <div class="text-center text-center">
                    <span
                      class="inline-block rounded-full w-2 h-2"
                      :class="{
                        'bg-success': product.available,
                        'bg-danger': !product.available,
                      }"
                    ></span>
                  </div>
                </td>
                <td class="text-center">
                  <input
                    type="checkbox"
                    class="checkbox"
                    data-testid="sliders-items-0-checkbox"
                    dusk="1-checkbox"
                    v-model="product.isFeatured"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </loading-card>
    </loading-view>
  </div>
</template>

<script>
export default {
  data: () => ({
    initialLoading: true,
    loading: false,
    products: [],
    search: "",
  }),
  mounted() {
    this.getProducts().then(() => {
      this.initialLoading = false;
    });
  },
  methods: {
    getProducts() {
      return Nova.request()
        .get("/nova-vendor/featured-products/products")
        .then((response) => {
          this.products = response.data.products || [];
        });
    },
    updateProducts() {
      this.loading = true;

      return Nova.request()
        .post("/nova-vendor/featured-products/products", {
          products: this.products,
        })
        .then(() => {
          this.$toasted.show("Featured products have been updated.", {
            type: "success",
          });
          this.loading = false;
        })
        .catch((error) => {
          this.$toasted.show(error.response.data.message, {
            type: "error",
          });
        });
    },
  },
  computed: {
    filteredProducts() {
      return this.products.filter((product) => {
        return (
          product.title.toLowerCase().includes(this.search.toLowerCase()) ||
          product.merchant.name
            .toLowerCase()
            .includes(this.search.toLowerCase()) ||
          product.price.toLowerCase().includes(this.search.toLowerCase())
        );
      });
    },
  },
};
</script>

<style>
/* Scoped Styles */
</style>
