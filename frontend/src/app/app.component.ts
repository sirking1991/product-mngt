import { Component } from '@angular/core';
import { ProductsService } from './services/products.service';
import { Product } from './product';

declare var $: any;

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  products: Product[];

  productData: Product = null;

  newRecord: boolean;
  messageType: string = 'ERROR';
  message: string = '';

  viewType: string = 'ALL';

  constructor(private rest: ProductsService) {
    this.getProducts();
  }

  getProducts() {
    if ('DUPLICATENAMES' == this.viewType) {
      this.rest.getDuplicateNames().subscribe((data: Product[]) => {
        this.products = data;
      });
    } else {
      this.rest.getProducts().subscribe((data: Product[]) => {
        this.products = data;
      });
    }

  }

  loadDuplicateNames() {
    this.viewType = 'DUPLICATENAMES';
    this.getProducts();
  }

  loadAllProducts() {
    this.viewType = 'ALL';
    this.getProducts();
  }

  open(id) {
    this.newRecord = true;
    this.message = '';
    this.messageType = '';
    this.productData = { id: 0, code: '', name: '', url: '', created_at: null, edited_at: null }

    // if id was passed, then set productData
    if (-1 != id) {
      this.productData = JSON.parse(JSON.stringify(this.products[id]));
      this.newRecord = false
    }

    $('#myModal').modal('show');
  }

  saveBtnClick() {
    if (this.newRecord)
      this._addProduct();
    else
      this._updateProduct();
  }

  _addProduct() {
    this.rest.addProduct(this.productData).subscribe((result) => {
      $('#myModal').modal('hide');
      this.getProducts();
    }, (err) => {
      this._handleError(err);
    });
  }

  _updateProduct() {
    this.rest.updateProduct(this.productData).subscribe((result) => {
      $('#myModal').modal('hide');
      this.getProducts();
    }, (err) => {
      this._handleError(err);
    });
  }

  _handleError(err) {
    if (typeof err.error === 'object') {
      this.messageType = 'ERROR';
      this.message = '';
      if (undefined != err.error.code) this.message += ' ' + err.error.code[0];
      if (undefined != err.error.name) this.message += ' ' + err.error.name[0];
      if (undefined != err.error.url) this.message += ' ' + err.error.url[0];
    } else {
      if (409 == err.status) {
        this.messageType = 'ERROR';
        this.message = err.error
      }
    }
  }

  delete() {
    if (!confirm('Are you sure you want to delete?')) return;

    this.rest.deleteProduct(this.productData).subscribe((result) => {
      $('#myModal').modal('hide');
      this.getProducts();
    }, (err) => {
      this._handleError(err);
    });
  }


}