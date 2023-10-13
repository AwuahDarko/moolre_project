import { Component, OnInit, EventEmitter,Output,AfterViewInit, Input } from '@angular/core';
import { Utility } from '../utils/utility';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Constants } from '../utils/constants';


@Component({
  selector: 'app-momo-ui',
  templateUrl: './momo-ui.component.html',
  styleUrls: ['./momo-ui.component.css']
})
export class MomoUiComponent implements OnInit, AfterViewInit {

  @Output() openOtp: EventEmitter<any> = new EventEmitter();

  loading = false;
  @Input() api_key: string = ""
  @Input() amount: string = ""
  @Input() unique_id: string = ""
  @Input() asset_path: string = '../../'

  constructor(private _http: HttpClient,) { }

  ngOnInit(): void {}

  ngAfterViewInit(): void{
    this.hideError()
  }

  async makePayment(event: Event): Promise<void>{
    event.preventDefault()
    this.hideError()

    const phone = (document.getElementById('devjack-phone') as HTMLInputElement).value
    const provider = (document.getElementById('devjack-provider') as HTMLInputElement).value
    const amount = (document.getElementById('devjack-amount') as HTMLInputElement).value
   

    if(!Utility.validatePhoneNumber(phone)){
      this.showError("Phone number is invalid")
      return;
    }

    if(provider === "0"){
      this.showError("Please a provider")
      return;
    }

    if(!Utility.validateNumber(amount)){
      this.showError("Please enter an amount")
      return;
    }

    const headerOptions = new HttpHeaders({
      'Authorization': 'Bearer ' + this.api_key,
      'Content-Type': 'application/json'
    });

    const body = {
      phone: phone,
      provider: provider,
      unique_id: this.unique_id,
      amount: this.amount
    }

    
    this.loading = true;

     this._http.post(`${Constants.url}${'/initiate-momo'}`, body, { observe: 'response', headers: headerOptions })
     .subscribe(response => {
      console.log(response['body'])
      this.loading = false;

      const body:any = Utility.parse(response['body'])
      if(body.status == 200){
        this.openOtp.emit({
          transaction_id: body.transaction_id,
          otp_code: body.otp_code
        });
      }
     }, error => {
      this.loading = false
      console.log(error)
      this.showError(error['message'])
     });    
   
  }

  showError(msg:string):void{
   const errorElement = document.getElementById('devjack-error') 
   errorElement!.hidden = false;
   errorElement!.innerHTML = msg;
  }

  hideError():void{
    const errorElement = document.getElementById('devjack-error') 
   errorElement!.hidden = true;
   errorElement!.innerHTML = "";
  }

  
}
