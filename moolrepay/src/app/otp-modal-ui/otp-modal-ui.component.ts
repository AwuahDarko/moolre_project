import { Component, OnInit, Input, Output, EventEmitter, AfterViewInit } from '@angular/core';

@Component({
  selector: 'app-otp-modal-ui',
  templateUrl: './otp-modal-ui.component.html',
  styleUrls: ['./otp-modal-ui.component.css']
})
export class OtpModalUiComponent implements OnInit, AfterViewInit {

  @Input() code: string = ""
  @Output() onClose = new EventEmitter<any>();
  @Input() mode: string = ''


  constructor() { }

  ngOnInit(): void {


  }

  ngAfterViewInit(): void {
    if (this.mode === 'success') {
      this.showMessage('Simulating successful payment now')
    } else {
      this.showMessage('Simulating failed payment now')
    }

  }


  verifyOtp(event: Event): void {
    event.preventDefault()
    this.hideMessage()
    const inputCode = (document.getElementById('devjack-code') as HTMLInputElement).value

    if (inputCode === this.code) {
      this.onClose.emit(true)
      return
    }

    this.showMessage('Invalid code')
    // TODO =========
    console.log('code from', this.code)

  }

  showMessage(msg: string): void {
    const node = document.getElementById('devjack-msg')
    node!.hidden = false
    node!.innerHTML = msg;
  }

  hideMessage(): void {
    const node = document.getElementById('devjack-msg')
    node!.hidden = true
    node!.innerHTML = "";
  }

  getRandomInt(min: number, max: number): number {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }
}
