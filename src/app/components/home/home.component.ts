import { Component, OnInit } from '@angular/core';
import { ProductsService } from '../../services/products.service';
import { environment } from '../../../environments/environment';


@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  totalAll=0;
  total=0;
  products:any;
  limit=8;
  offset=8;
  showLoadMore:boolean=false;
  baseUrl = `${environment.BASE_URL}`+ '/';
  constructor(private productsService: ProductsService ) { }

  ngOnInit(): void {
    this.productsService.fetchAllProducts({limit:10000, offset:0}).pipe()
    .subscribe((response: any) => {
      this.totalAll=response.length;
    });
    this.productsService.fetchAllProducts({limit:this.limit, offset:0}).pipe()
    .subscribe((response: any) => {
      this.products = response;
      this.total=response.length;
      this.showLoadMore = (this.total<this.totalAll)?true:false;
    });
  }

  loadMore(){
    this.productsService.fetchAllProducts({limit:this.limit, offset:this.offset}).pipe()
    .subscribe((response: any) => {
      this.offset = this.offset+8;
      this.products = this.products.concat(response);
      this.total=this.products.length;
      this.showLoadMore = (this.total<this.totalAll)?true:false;
    });
  }

}
