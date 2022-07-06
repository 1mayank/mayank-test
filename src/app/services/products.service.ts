import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs/operators';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ProductsService {

  constructor(private http: HttpClient) { }

  // fetching products
  fetchAllProducts(params) {
    const url = `${environment.API_URL}/products/all`;
    return this.http.post(url,params).pipe(map(response => {
      return response;
    }));
  }
}
