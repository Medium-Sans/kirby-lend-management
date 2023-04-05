import Dashboard from "./components/Dashboard.vue";
import Inventory from "./components/Inventory.vue";
import Category from "./components/Category.vue";
import Item from "./components/Item.vue";
import Borrowers from "./components/Borrowers.vue";
import Lend from "./components/Lend.vue";
import LendAdd from "./components/LendAdd.vue";

panel.plugin("scardoso/kirby-lendmanagement", {
  components: {
    "k-dashboard-view": Dashboard,
    "k-inventory-view": Inventory,
    "k-borrowers-view": Borrowers,
    "k-category-view": Category,
    "k-item-view": Item,
    "k-lend-view": Lend,
    "k-lend-add-view": LendAdd
  }
});
