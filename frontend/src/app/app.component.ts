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
  messageType: string ='ERROR';
  message: string = '';

  constructor(private rest: ProductsService) {
    this.getProducts();
  }

  getProducts() {
    this.products = [];
    this.rest.getProducts().subscribe((data:Product[]) => {
      this.products = data;
    });
  }

  open(i) {
    this.newRecord = true;
    this.message = '';
    this.messageType = '';
    this.productData = {id:0, code:'', name:'', url:'', created_at: null, edited_at: null}

    // if id was passed, then set productData
    if (-1 != i) {
      this.productData = JSON.parse(JSON.stringify(this.products[i]));
      this.newRecord = false
    }

    $('#myModal').modal('show');
  }

  saveBtnClick() 
  {
    if (this.newRecord)
      this._addProduct();      
    else
      this._updateProduct();
  }

  _addProduct() 
  {
    this.rest.addProduct(this.productData).subscribe((result) => {
      $('#myModal').modal('hide');
      this.getProducts();
    }, (err) => {
      this._handleError(err);
    });
  }

  _updateProduct() 
  {
    this.rest.updateProduct(this.productData).subscribe((result) => {
      $('#myModal').modal('hide');
      this.getProducts();
    }, (err) => {
      this._handleError(err);
    });
  }

  _handleError(err) {
    console.log(err);
    if (409==err.status) {
      this.messageType='ERROR';
      this.message = err.error
    }
  }

  delete()
  {
    if (!confirm('Are you sure you want to delete?')) return;

    this.rest.deleteProduct(this.productData).subscribe((result) => {
      $('#myModal').modal('hide');
      this.getProducts();
    }, (err) => {
      this._handleError(err);
    });    
  }


}