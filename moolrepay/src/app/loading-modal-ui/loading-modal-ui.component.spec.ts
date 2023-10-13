import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LoadingModalUiComponent } from './loading-modal-ui.component';

describe('LoadingModalUiComponent', () => {
  let component: LoadingModalUiComponent;
  let fixture: ComponentFixture<LoadingModalUiComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ LoadingModalUiComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(LoadingModalUiComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
