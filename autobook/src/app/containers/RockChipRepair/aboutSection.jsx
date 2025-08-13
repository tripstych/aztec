import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import { SCREENS } from "../../components/responsive";
import ChipImg from "../../components/assets/images/chip.jpg";

const AboutUsContainer = styled.div`
  ${tw`
    w-full
    flex
    flex-wrap
    items-center
    justify-center
    pt-4
    pb-4
    pr-7
    pl-7
    md:pl-0
    md:pr-0
  `};
`;

const CarContainer = styled.div`
  width: auto;
  height: 10em;

  img {
    width: auto;
    height: 100%;
  }

  @media (min-width: ${SCREENS.md}) {
    height: 10em;
    margin-left: 1.5em;
  }

  @media (min-width: ${SCREENS.lg}) {
    height: 15em;
  }

  @media (min-width: ${SCREENS["2xl"]}) {
    height: 20em;
    margin-left: 0;
  }
`;

const InfoContainer = styled.div`
  ${tw`
    mb-4
    md:w-1/2
    flex
    flex-col
  `};
`;

const InfoText = styled.p`
  ${tw`
    md:max-w-xl
    text-sm
    md:text-base
    text-black
    font-normal
    mt-4
  `};
`;

const PriceText = styled.span`
  ${tw`
    font-bold
    text-cBlue
  `}
`;

const AboutSection = () => {
  return (
    <AboutUsContainer>
      <InfoContainer>
        <InfoText>
          At Aztec Auto Glass , we offer efficient and affordable rock chip
          repair to keep your windshield in top condition. For just{" "}
          <PriceText>$42.95</PriceText>, we'll expertly repair one chip, with
          each additional chip repaired for only <PriceText>$19.95</PriceText>.
        </InfoText>
        <InfoText>
          For those who want year-round peace of mind, our subscription plan is
          an unbeatable value. For just <PriceText>$99</PriceText>, enjoy
          unlimited rock chip repairs throughout the year, ensuring your
          windshield remains free of damage no matter how many chips you
          encounter.
        </InfoText>
        <InfoText>
          Protect your investment and maintain your vehicle's safety with our
          reliable rock chip repair services. Click below to get started or
          contact us for more details! No Appointment needed!
        </InfoText>
      </InfoContainer>
      <CarContainer>
        <img src={ChipImg} alt="" />
      </CarContainer>
    </AboutUsContainer>
  );
};

export default AboutSection;
