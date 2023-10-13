import { Component, OnInit, Input, AfterViewInit, EventEmitter, Output } from '@angular/core';
import { Utility } from '../utils/utility';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Constants } from '../utils/constants';


@Component({
  selector: 'app-card-ui',
  templateUrl: './card-ui.component.html',
  styleUrls: ['./card-ui.component.css']
})
export class CardUiComponent implements OnInit, AfterViewInit {

  @Output() onPaymentDone: EventEmitter<any> = new EventEmitter();
  @Input() api_key: string = ""
  @Input() amount: string = ""
  @Input() unique_id: string = ""
  @Input() mode: string = ""
  @Input() asset_path: string = '../../'
  loading = false;



  constructor(private _http: HttpClient,) { }

  ngOnInit(): void { }

  ngAfterViewInit(): void {
    this.hideError()

    if (this.mode === 'success') {
      this.showError('Simulating successful payment now')
    } else {
      this.showError('Simulating failed payment now')
    }
  }

  async makePayment(event: Event): Promise<void> {
    event.preventDefault()
    this.hideError()


    const email = (document.getElementById('devjack-email') as HTMLInputElement).value
    const card = (document.getElementById('devjack-card') as HTMLInputElement).value
    const expire = (document.getElementById('devjack-expire') as HTMLInputElement).value
    const cvc = (document.getElementById('devjack-cvc') as HTMLInputElement).value

    if (!Utility.validateEmail(email)) {
      this.showError("Email is invalid")
      return;
    }

    const headerOptions = new HttpHeaders({
      'Authorization': 'Bearer ' + this.api_key,
      'Content-Type': 'application/json'
    });

    const body = {
      email: email,
      card: card,
      unique_id: this.unique_id,
      amount: this.amount,
      expire,
      cvc,
      status: this.mode 
    }


    this.loading = true;
    this._http.post(`${Constants.url}${'/initiate-card'}`, body, { observe: 'response', headers: headerOptions })
      .subscribe(response => {
        console.log(response['body'])
        this.loading = false;

        const body: any = Utility.parse(response['body'])
        if (body.status == 200) {
          this.onPaymentDone.emit({
            transaction_id: body.transaction_id,
            message: body.message,
            'status': true
          });
        } else {
          this.onPaymentDone.emit({
            transaction_id: body.transaction_id,
            message: body.message,
            'status': false
          });
        }

        this.clear()
      }, error => {
        this.loading = false
        console.log(error)
        this.showError(error['message'])
      });


  }

  onDateEntered(event: Event): void {
    (event.target as HTMLInputElement).value = Utility.formatExpiryDate((event.target as HTMLInputElement).value)
  }

  showError(msg: string): void {
    const errorElement = document.getElementById('devjack-error')
    errorElement!.hidden = false;
    errorElement!.innerHTML = msg;
  }

  hideError(): void {
    const errorElement = document.getElementById('devjack-error')
    errorElement!.hidden = true;
    errorElement!.innerHTML = "";
  }


  clear(): void {
    (document.getElementById('devjack-email') as HTMLInputElement).value = '';
    (document.getElementById('devjack-card') as HTMLInputElement).value = '';
    (document.getElementById('devjack-expire') as HTMLInputElement).value = '';
    (document.getElementById('devjack-cvc') as HTMLInputElement).value = ''
    if (this.mode == 'success') {
      this.mode = 'failed'
    } else {
      this.mode = 'success'
    }


  }
}
