import { Component } from '@angular/core';
import { ProductsService } from './services/products.service';

declare var $: any;

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  products:any = [];

  productData = { code:'', name:'', url:'' };

  newRecord: boolean;
  messageType: string ='ERROR';
  message: string = '';

  constructor(private rest: ProductsService) {
    this.getProducts();
  }

  getProducts() {
    this.products = [];
    this.rest.getProducts().subscribe((data: {}) => {
      console.log(data);
      this.products = data;
    });
  }

  open(i) {
    this.newRecord = true
    this.productData = {
      code:'', name:'', url:''
    }
    // if id was passed, then set productData
    if( -1!=i ) {
      this.productData = this.products[i];
      this.newRecord = false
    }

    $('#myModal').modal('show');
  }

  saveBtnClick() {
    if( this.newRecord ) {
      this._updateProduct();
    } else {
      this._addProduct();
    }
  }

  _addProduct() {
    this.rest.addProduct(this.productData).subscribe((result) => {
      this.messageType = 'INFO';
      this.message = "Record saved";
    }, (err) => {
      console.log(err);
    });
  }

  _updateProduct() {
    this.rest.updateProduct(this.productData).subscribe((result) => {
      this.messageType = 'INFO';
      this.message = "Record updated";
    }, (err) => {
      console.log(err);
    });
  }


}