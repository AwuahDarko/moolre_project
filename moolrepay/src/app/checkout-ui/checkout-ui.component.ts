import { Component, OnInit, Input } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Constants } from '../utils/constants';
import { Utility } from '../utils/utility';



@Component({
  selector: 'app-checkout-ui',
  templateUrl: './checkout-ui.component.html',
  styleUrls: ['./checkout-ui.component.css']
})
export class CheckoutUiComponent implements OnInit {



  @Input() api_key: string = "pk_86504ef6fe2432d83f2d5a64714511113f088b6bae882b1e933fcbf5faafa20e"
  @Input() amount: string = "100"
  @Input() unique_id: string = "8764fKIIJ"

  isMomo: boolean = true
  showModal: boolean = false
  showOptModal: boolean = false;
  otp_code: string = ''
  transaction_id: string = ''
  loading: boolean = false
  modal_msg: string = ''
  is_error_msg: boolean = false
  mode = ''
  count = 2;
  @Input() asset_path:string = '../../'

  constructor(private _http: HttpClient,) {
    
   }

  ngOnInit(): void {
  }

  generateStatus() {
    if (this.count % 2 === 0) {
      this.mode = 'success';
    } else {
      this.mode = 'failed'
    }
    ++this.count;
  }

  onOpenOtpModal(data: any): void {
    this.showOptModal = true
    this.otp_code = data.otp_code.toString()
    this.transaction_id = data.transaction_id.toString()
    this.generateStatus()
  }

  onOtpFinished(done: boolean) {

    this.showOptModal = false
    if (done) {
      this.proceedAfterOtp()
    }
  }

  proceedAfterOtp(): void {


    const headerOptions = new HttpHeaders({
      'Authorization': 'Bearer ' + this.api_key,
      'Content-Type': 'application/json'
    });

    const body = {
      opt_verified: true,
      status: this.mode,
      unique_id: this.unique_id,
      transaction_id: this.transaction_id
    }

    this.loading = true;

    this._http.post(`${Constants.url}${'/proceed-momo'}`, body, { observe: 'response', headers: headerOptions })
      .subscribe(response => {
        console.log(response['body'])
        this.loading = false;

        const body: any = Utility.parse(response['body'])
        if (body.status == 200) {
          this.showModal = true
          this.modal_msg = body.message
          this.is_error_msg = false
        }
      }, error => {
        this.loading = false
        this.showModal = true
        this.modal_msg = error['error']['message']
        this.is_error_msg = true
        console.log(error)
      });
  }

  cardPayment(event: any) {
    if (event.status == true) {
      this.showModal = true
      this.modal_msg = event.message
      this.is_error_msg = false
    } else {
      this.showModal = true
      this.modal_msg = event.message
      this.is_error_msg = true
    }
  }

  gotoCard() {
    this.isMomo = false
    this.generateStatus()
  }
}
