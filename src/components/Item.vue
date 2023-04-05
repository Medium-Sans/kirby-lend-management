<template>
  <k-inside>
    <k-view>
      <k-header>
        {{ item.title }}
        <k-button-group slot="left">
          <k-button
            text="Delete"
            icon="trash"
            @click="$dialog('inventory/item/' + item.id + '/delete')"
          />
          <k-button
            text="Print"
            icon="print"
            @click="print"
          />
        </k-button-group>

        <k-button-group slot="right">
          <k-button
            icon="add"
            @click="$dialog('/inventory/category/create')"
          >
            {{ $t('lendmanagement.category.add') }}
          </k-button>
        </k-button-group>
      </k-header>

      <k-grid gutter="large">
        <k-column width="2/3">

          <k-form v-model="item" @input="input" @submit="submit" :fields="{
            title: {
              label: $t('lendmanagement.item.form.title'),
              type: 'text',
              required: true,
              width: '1/2'
            },
            quantity: {
              label: $t('lendmanagement.item.form.quantity'),
              type: 'number',
              required: true,
              width: '1/2'
            },
            description: {
              label: $t('lendmanagement.item.form.description'),
              type: 'textarea',
              required: false,
              width: '1'
            },
            categoryId: {
              label: $t('lendmanagement.category'),
              type: 'multiselect',
              required: true,
              search: true,
              options: categories,
              max: 1,
              width: '1'
            },
            notes: {
              label: $t('lendmanagement.item.form.note'),
              type: 'textarea',
              required: false,
              width: '1'
            },
          }"/>

          <k-button icon="check" @click="submit">Save</k-button>
        </k-column>

        <k-column width="1/3">
          <k-field label="Qr-Code">
            <k-image class="k-image"
                     :src="item.qr_code"
                     ratio="1/1"/>
          </k-field>
        </k-column>
      </k-grid>
    </k-view>
  </k-inside>
</template>

<script>
export default {
  props: {
    item: Object,
    categories: Array
  },
  methods: {
    forceFileDownload(response, title) {
      const url = window.URL.createObjectURL(new Blob([response], {encoding:"UTF-8", type:"text/plain;charset=UTF-8"} ))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', title)
      document.body.appendChild(link)
      link.click()
    },
    submit() {
      this.$api.post('lendmanagement/item/' + this.item.id + '/update', this.item);
    },
    async print() {
      const label = await this.$api.post('lendmanagement/item/' + this.item.id + '/print');
      this.forceFileDownload(atob(label.data), this.item.id + '-item.label');
    }
  }
};
</script>

<style>
.k-image {
  height: 10px;
  width: auto;
}
</style>
