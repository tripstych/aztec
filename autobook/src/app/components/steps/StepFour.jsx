import React, { useEffect, useRef, useState } from "react";
import styled from "styled-components";
import tw from "twin.macro";
import emailjs from "@emailjs/browser";
import { useBooking } from "../../hooks/useBooking";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faCalendarDays,
  faCar,
  faNewspaper,
  faClock,
  faWrench,
  faClockRotateLeft,
} from "@fortawesome/free-solid-svg-icons";
import { toast } from "react-toastify";
import { AdditionalOptions } from "../../utils/constants";

const Container = styled.div`
  ${tw`
    grid
    grid-cols-1
    md:grid-cols-2
    gap-8
    w-full
    max-w-7xl
    mx-auto
    p-8
  `}
`;

const SummarySection = styled.div`
  ${tw`
    grid
    gap-8
    grid-cols-1
    md:grid-cols-2
    justify-items-center
  `}
`;

const SummaryItem = styled.div`
  ${tw`
    flex
    flex-col
    items-center
    justify-center
    text-center
    bg-white
    rounded-lg
    p-8
    shadow-md
    w-full
    max-w-sm
    space-y-2
  `}
`;

const SummaryLabel = styled.div`
  ${tw`
    text-cBlue
    uppercase
    font-bold
    text-lg
    pb-4
  `}
`;

const SummaryValue = styled.div`
  ${tw`
    text-black
    font-extrabold
    text-3xl
    mt-1
  `}
`;

const FormSection = styled.div`
  ${tw`
    bg-black
    text-white
    rounded-lg
    p-10
    shadow-lg
  `}
`;

const FormTitle = styled.h2`
  ${tw`
    text-2xl
    font-bold
    text-cBlue
    mb-4
  `}
`;

const FormSubtitle = styled.h3`
  ${tw`
    text-lg
    font-bold
    text-cBlue
  `}
`;

const FormDescription = styled.p`
  ${tw`
    text-gray-200
    text-sm
    font-medium
    mb-8
  `}
`;

const Form = styled.form`
  ${tw`
    space-y-6
  `}
`;

const Input = styled.input`
  ${tw`
    w-full
    p-3
    rounded-lg
    text-black
    border
    border-gray-300
  `}
`;

const AdditionalInfoContainer = styled.div`
  ${tw`
   space-y-6 
  `}
`;

const TextArea = styled.textarea`
  ${tw`
    w-full
    p-3
    rounded-lg
    text-black
    border
    border-gray-300
  `}
`;

const SubmitButton = styled.button`
  ${tw`
    w-full
    p-3
    uppercase
    bg-cBlue
    rounded-lg
    text-white
    font-bold
    text-lg
  `}
  ${({ $isComplete }) =>
    $isComplete
      ? `
        transition: transform 0.1s ease-in-out;

        &:hover {
          transform: scale(0.95);
        }
      `
      : tw`bg-gray-700 text-gray-500`}
`;

const DetailIcon = styled(FontAwesomeIcon)`
  ${tw`
    text-cBlue
    font-bold
    text-3xl
  `}
`;

const StepFour = () => {
  const form = useRef();
  const { bookingData } = useBooking();
  const [isReady, setIsReady] = useState(false);

  const totalDuration = bookingData.service
    .map((s) => parseInt(s.duration)) // Convert "90 min" to 90
    .filter((duration) => !isNaN(duration)) // Filter out invalid durations
    .reduce((total, current) => total + current, 0); // Sum up all durations

  const WithAvatar = () => {
    return (
      <div className="flex flex-col pl-8">
        <div className="grid z-10 place-items-center absolute -left-12 top-1/2 -translate-y-1/2 size-20 rounded-full shadow-lg bg-black text-white">
          <img src="/images/aztec_bg.png" alt="logo" />
        </div>
        <p className="text-white font-semibold">Aztec Auto Glass</p>
        <p className="text-sm text-zinc-400">
          Your quote has been sent successfully!
        </p>
      </div>
    );
  };

  // Validate form fields and steps
  useEffect(() => {
    const isBookingComplete =
      bookingData.carType &&
      bookingData.year &&
      bookingData.make &&
      bookingData.model &&
      bookingData.service.length > 0 &&
      bookingData.selectedDate &&
      bookingData.selectedTime;

    setIsReady(isBookingComplete);
  }, [bookingData]);

  const sendEmail = (e) => {
    e.preventDefault();

    const serviceID = process.env.REACT_APP_SERVICE_ID;
    const templateID = process.env.REACT_APP_TEMPLATE_ID;
    const userPublicID = process.env.REACT_APP_EMAIL_PUBLIC_KEY;
    const formData = new FormData(form.current);
    const formFields = Object.fromEntries(formData.entries());

    const selectedSensors = [
      formFields.rain_sensors && "Rain Sensors",
      formFields.rain_lane_sensors && "Rain & Lane Sensors",
      formFields.heads_up_display && "Heads-Up Display",
      formFields.not_sure && "Not Sure",
    ]
      .filter(Boolean)
      .join(", ");

    const emailParams = {
      ...formFields,
      company_name: "Aztec",
      car_type: bookingData.carType,
      year: bookingData.year,
      make: bookingData.make,
      model: bookingData.model,
      sensors: selectedSensors || "None",
      service: bookingData.service
        .map(
          (s) =>
            `<div style="color: #000000; font-weight: 800; font-size: 1.2rem; line-height: 2.25rem;">
              ${s.name}
            </div>`
        )
        .join(""), // Join all services into a single string
      selected_date: bookingData.selectedDate,
      selected_time: bookingData.selectedTime,
      estimated_duration: totalDuration > 0 ? `${totalDuration} min` : "-",
    };

    emailjs.send(serviceID, templateID, emailParams, userPublicID).then(
      () => {
        // Clear the form fields
        form.current.reset();
        toast(WithAvatar, {
          className:
            "shadow-lg overflow-visible scale-100 ring-1 ring-black/5 rounded-xl flex items-center gap-6 bg-slate-800 highlight-white/5",
        });
      },
      (error) => {
        toast(`Quote failed to send: ${error.text}`);
      }
    );
  };

  return (
    <Container>
      {/* Summary Section */}
      <SummarySection>
        <SummaryItem>
          <DetailIcon icon={faCar} />
          <SummaryLabel>Car Type</SummaryLabel>
          <SummaryValue>{bookingData.carType || "-"}</SummaryValue>
        </SummaryItem>
        <SummaryItem>
          <DetailIcon icon={faNewspaper} />
          <SummaryLabel>Year / Make / Model</SummaryLabel>
          <SummaryValue>
            {`${bookingData.year} ${bookingData.make} ${bookingData.model}` ||
              "-"}
          </SummaryValue>
        </SummaryItem>
        <SummaryItem>
          <DetailIcon icon={faWrench} />
          <SummaryLabel>Service</SummaryLabel>

          {bookingData.service.map((s, idx) => (
            <SummaryValue key={idx}>{s.name}</SummaryValue>
          )) || "-"}
        </SummaryItem>
        <SummaryItem>
          <DetailIcon icon={faCalendarDays} />
          <SummaryLabel>Selected Date</SummaryLabel>
          <SummaryValue>{bookingData.selectedDate || "-"}</SummaryValue>
        </SummaryItem>
        <SummaryItem>
          <DetailIcon icon={faClock} />
          <SummaryLabel>Selected Time</SummaryLabel>
          <SummaryValue>{bookingData.selectedTime || "-"}</SummaryValue>
        </SummaryItem>
        <SummaryItem>
          <DetailIcon icon={faClockRotateLeft} />
          <SummaryLabel>Est. Duration</SummaryLabel>
          <SummaryValue>
            {totalDuration > 0 ? `${totalDuration} min` : "-"}
          </SummaryValue>
        </SummaryItem>
      </SummarySection>

      {/* Form Section */}
      <FormSection>
        <FormTitle>Your Contact Details</FormTitle>
        <FormDescription>
          The request will be sent to us and an associate will get in touch to
          confirm your booking.
        </FormDescription>
        <Form ref={form} onSubmit={sendEmail}>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Input
              type="text"
              name="customer_first"
              placeholder="First Name *"
              required
            />
            <Input type="text" name="customer_last" placeholder="Last Name *" />
          </div>
          <Input
            type="email"
            name="customer_email"
            placeholder="Email *"
            required
          />
          <Input
            type="tel"
            name="customer_phone"
            placeholder="Phone Number *"
            required
          />
          <AdditionalInfoContainer>
            <FormSubtitle>Additional Information</FormSubtitle>

            <div className="grid grid-cols-2 gap-4">
              {AdditionalOptions.map((option) => (
                <div key={option.name} className="flex items-center gap-2">
                  <input
                    type="checkbox"
                    name={option.name}
                    id={option.name}
                    value="Yes"
                    className="accent-cBlue size-4"
                  />
                  <label
                    htmlFor={option.name}
                    className="text-white whitespace-nowrap"
                  >
                    {option.label}
                  </label>
                </div>
              ))}
            </div>

            <Input
              type="text"
              name="vin_number"
              placeholder="VIN Number (optional)"
              className="mt-4"
            />

            <TextArea
              rows="4"
              name="customer_additionalInfo"
              placeholder="Additional Information"
            />
          </AdditionalInfoContainer>

          <SubmitButton type="submit" disabled={!isReady} $isComplete={isReady}>
            Get Quote
          </SubmitButton>
        </Form>
      </FormSection>
    </Container>
  );
};

export default StepFour;
