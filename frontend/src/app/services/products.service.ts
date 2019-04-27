import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map, tap } from 'rxjs/operators';

import { Product } from '../product';

const endpoint = 'http://localhost:8000/products';
const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type': 'application/json'
  })
};

@Injectable({
  providedIn: 'root'
})
export class ProductsService {

  constructor(private http: HttpClient) { }

  private extractData(res: Response) {
    let body = res;
    return body || {};
  }

  getProducts(): Observable<any> {
    return this.http.get(endpoint).pipe(
      map(this.extractData));
  }

  getDuplicateNames(): Observable<any> {
    return this.http.get(endpoint + '/duplicatenames').pipe(
      map(this.extractData));
  }

  getProduct(id): Observable<any> {
    return this.http.get(endpoint + 'products/' + id).pipe(
      map(this.extractData));
  }

  addProduct(product): Observable<any> {
    console.log(product);
    return this.http.post<any>(endpoint, JSON.stringify(product), httpOptions).pipe(
      tap((product) => console.log(`added product w/ id=${product.id}`)),
    );
  }

  updateProduct(product: Product): Observable<any> {
    return this.http.put(endpoint + '/' + product.id, JSON.stringify(product), httpOptions).pipe(
      tap(_ => console.log(`updated product code=${product.code}`)),
    );
  }

  deleteProduct(product): Observable<any> {
    return this.http.delete<any>(endpoint + '/' + product.id, httpOptions).pipe(
      tap(_ => console.log(`deleted product code=${product.code}`)),
    );
  }

}
