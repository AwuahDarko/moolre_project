import { Component, OnInit } from '@angular/core';
import { Transaction } from '../models/transaction';

@Component({
  selector: 'app-merchant-ui',
  templateUrl: './merchant-ui.component.html',
  styleUrls: ['./merchant-ui.component.css']
})
export class MerchantUiComponent implements OnInit {

  transactions: Transaction[] = [];
  current_page: number = 1;
  total_pages: number = 100;
  container: number[] = [];

  constructor() {

    this.deducePages()
    for(let i=0;i<10;++i){
      this.transactions.push(new Transaction({
        'date': '2022-12-34',
        'amount': 'GHS 2000',
        'transactionId': '828378382',
        'status': i%2==0?'success': 'failed',
        'email': 'mjadarko@gmail.com',
        'phone': '99283484'
      }))
    }

  }

  ngOnInit(): void {
  }

  onPageChange(page: number): void {
    this.current_page = page;
    this.deducePages('page')
  }

  deducePages(move: string = 'initial') {
    let second_to_last_page: number = this.total_pages - 1;
    if (second_to_last_page <= 0) second_to_last_page = 1;
    let first_page = 1;

    // generate next 3 pages
    let next_pages: number[] = []


    if (move !== 'initial') {
      let temp: number[] = []
      for (let i = this.current_page; i > this.current_page - 3; --i) {
        if(i < 1) continue;
        temp.push(i)
      }

      for (let i of temp.reverse()) {
        next_pages.push(i)
      }
      if (!next_pages.includes(this.current_page) && !this.container.includes(this.current_page)) {
        next_pages.push(this.current_page)
      }

      this.container = [...next_pages];
      if (!this.container.includes(second_to_last_page)) {
        this.container.push(second_to_last_page)
      }

      if (!this.container.includes(this.total_pages)) {
        this.container.push(this.total_pages)
      }
    }else {
      for (let i = 2; i < 4; ++i) {
        if (i < this.total_pages) {
          next_pages.push(i)
        }
      }
      this.container = [first_page, ...next_pages, second_to_last_page, this.total_pages];
    }
  }

  onPreviousPage(): void {
    if (this.current_page === 1) return
    this.current_page -= 1;
    this.deducePages('prev')
  }

  onNextPage(): void {
    if (this.current_page === this.total_pages) return
    this.current_page += 1;
    this.deducePages('next')
  }
}
