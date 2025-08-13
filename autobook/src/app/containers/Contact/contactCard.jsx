import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faPhone } from "@fortawesome/free-solid-svg-icons";
import React, { useRef } from "react";
import styled from "styled-components";
import tw from "twin.macro";
// import emailjs from "@emailjs/browser";
import ShopFront from "../../components/assets/images/shop_front.jpg";

const SectionContainer = styled.div`
  ${tw`
    flex
    items-center
    justify-center
    w-full
  `}
`;

const ContactUsCard = styled.div`
  ${tw`
    flex
    flex-col
    items-center
    w-full
    max-w-screen-sm
    bg-white
    rounded-lg
    shadow-lg
    px-10
    py-8
    md:m-6
  `}
`;

const FormSection = styled.div`
  ${tw`
    bg-cBlack
    text-white
    rounded-lg
    shadow-lg
    justify-items-center
    max-w-screen-sm
    w-full
    px-10
    py-8
    md:m-6
  `}
`;

const StoreImage = styled.img`
  ${tw`
    w-full
    h-64
    object-cover
    mb-6
    rounded-lg
    shadow-lg
  `}
`;

const InfoSection = styled.div`
  ${tw`
    flex
    flex-col
    items-center
    text-center
    md:gap-4
    gap-2
  `}
`;

const StoreTitle = styled.h2`
  ${tw`
    text-2xl
    md:text-3xl
    font-extrabold
    text-cBlue
    md:mb-2
  `}
`;

const LocationInfo = styled.div`
  ${tw`
    flex
    items-center
    text-black
    font-medium
    md:mt-2
    text-sm
    md:text-lg
  `}
`;

const LocationIcon = styled(FontAwesomeIcon)`
  ${tw`
    mr-2
    text-lg
    text-cBlue
  `}
`;

const HoursSection = styled.div`
  ${tw`
    mt-6
    md:mt-8
    w-full
    flex
    flex-col
    justify-center
    items-center
    gap-2
  `}
`;

const HoursTitle = styled.h3`
  ${tw`
    text-xl
    font-bold
    text-black
    mb-4
  `}
`;

const HoursTable = styled.table`
  ${tw`
    w-full
    text-sm
    border-collapse
    text-center
  `}

  th,
  td {
    ${tw`
      p-2
      border-b
      text-center
    `}
  }

  th {
    ${tw`
      text-black
      font-bold
    `}
  }

  td {
    ${tw`
      text-black
      font-medium
    `}
  }
`;

const TableWrapper = styled.div`
  ${tw`
    flex
    justify-center
    items-center
    w-full
  `}
`;

const ContentWrapper = styled.div`
  ${tw`
    flex
    flex-col
    lg:flex-row
    w-full
    px-6
    lg:px-20
    max-w-screen-xl
  `}
`;

const FormTitle = styled.h2`
  ${tw`
    text-3xl
    md:text-4xl
    font-extrabold
    text-cBlue
    mb-4
  `}
`;

const FormDescription = styled.p`
  ${tw`
    text-gray-200
    text-sm
    md:text-lg
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
  transition: transform 0.1s ease-in-out;

  &:hover {
    transform: scale(0.95);
  }
`;

const ContactCard = () => {
  const form = useRef();

  // const sendEmail = (e) => {
  //   e.preventDefault();

  //   try {
  //     const serviceID = process.env.REACT_APP_SERVICE_ID;
  //     const templateID = process.env.REACT_APP_TEMPLATE_ID;
  //     const userPublicID = process.env.REACT_APP_EMAIL_PUBLIC_KEY;
  //     const formData = new FormData(form.current);
  //     const formFields = Object.fromEntries(formData.entries());

  //     const emailParams = {
  //       ...formFields,
  //     };

  //     emailjs.send(serviceID, templateID, emailParams, userPublicID);
  //     alert("Quote request has been sent successfully!");
  //   } catch (error) {
  //     alert("Failed to send email. Please try again.");
  //   }
  // };
  return (
    <SectionContainer>
      <ContentWrapper>
        <ContactUsCard>
          <StoreImage src={ShopFront} alt="Storefront" />
          <InfoSection>
            <StoreTitle>Airdrie, AB</StoreTitle>
            <LocationInfo>
              <LocationIcon icon={faLocationDot} />
              203 - 2914 Kingsview Boulevard SE
            </LocationInfo>
            <LocationInfo>
              <LocationIcon icon={faPhone} />
              <a href="tel:+15879667636">(587) 966-7636</a>
            </LocationInfo>
          </InfoSection>
          <HoursSection>
            <HoursTitle>Hours of Operation</HoursTitle>
            <TableWrapper>
              <HoursTable>
                <tbody>
                  <tr>
                    <th>Monday:</th>
                    <td>8:30 am - 5:00 pm</td>
                  </tr>
                  <tr>
                    <th>Tuesday:</th>
                    <td>8:30 am - 5:00 pm</td>
                  </tr>
                  <tr>
                    <th>Wednesday:</th>
                    <td>8:30 am - 5:00 pm</td>
                  </tr>
                  <tr>
                    <th>Thursday:</th>
                    <td>8:30 am - 5:00 pm</td>
                  </tr>
                  <tr>
                    <th>Friday:</th>
                    <td>8:30 am - 5:00 pm</td>
                  </tr>
                  <tr>
                    <th>Saturday:</th>
                    <td>9:00 am - 4:00 pm</td>
                  </tr>
                  <tr>
                    <th>Sunday:</th>
                    <td>Closed</td>
                  </tr>
                </tbody>
              </HoursTable>
            </TableWrapper>
          </HoursSection>
        </ContactUsCard>
        <FormSection>
          <FormTitle>Message Us</FormTitle>
          <FormDescription>
            Any comments, questions, or concerns send us a message!
          </FormDescription>
          <Form ref={form}>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Input
                type="text"
                name="customer_first"
                placeholder="First Name *"
                required
              />
              <Input
                type="text"
                name="customer_last"
                placeholder="Last Name *"
              />
            </div>
            <Input
              type="email"
              name="customer_email"
              placeholder="Email *"
              required
            />
            <TextArea
              rows="4"
              name="customer_message"
              placeholder="Your Message"
            />
            <SubmitButton type="submit">Send</SubmitButton>
          </Form>
        </FormSection>
      </ContentWrapper>
    </SectionContainer>
  );
};

export default ContactCard;
