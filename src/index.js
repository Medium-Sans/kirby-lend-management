import Lends from "./components/Lends.vue";
import Inventory from "./components/Inventory.vue";
import Borrowers from "./components/Borrowers.vue";
import Lend from "./components/Lend.vue";
import LendAdd from "./components/LendAdd.vue";
import Categories from "./components/Categories.vue";
import Item from "./components/Item.vue";

panel.plugin("mediumsans/kirby-lend-management", {
  components: {
    "k-lends-view": Lends,
    "k-inventory-view": Inventory,
    "k-borrowers-view": Borrowers,
    "k-categories-view": Categories,
    "k-item-view": Item,
    "k-lend-view": Lend,
    "k-lend-add-view": LendAdd
  }
});
