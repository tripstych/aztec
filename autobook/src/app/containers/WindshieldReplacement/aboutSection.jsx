import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import { SCREENS } from "../../components/responsive";
import SedanImg from "../../components/assets/images/sedan_1.png";

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
  height: 15em;

  img {
    width: auto;
    height: 100%;
  }

  @media (min-width: ${SCREENS.md}) {
    height: 20em;
  }

  @media (min-width: ${SCREENS.lg}) {
    height: 22em;
  }

  @media (min-width: ${SCREENS["2xl"]}) {
    height: 30em;
    margin-left: 0;
  }
`;

const InfoContainer = styled.div`
  ${tw`
    md:w-1/2
    flex
    flex-col
  `};
`;

const Title = styled.h1`
  ${tw`
    text-black
    text-2xl
    md:text-3xl
    font-extrabold
    md:font-black
    md:leading-normal
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

const SpanText = styled.span`
  ${tw`
    font-bold
    text-cBlue
    cursor-pointer
  `}
  display: inline-block;
  transition: transform 0.1s ease-in-out; /* Smooth scaling transition */

  &:hover {
    transform: scale(0.95); /* Increase size on hover */
  }
`;

const AboutSection = () => {
  return (
    <AboutUsContainer>
      <InfoContainer>
        <Title>Windshield Replacement</Title>
        <InfoText>
          Our in-shop windshield replacement service offers everything you'd
          expect from a top-notch auto glass provider, plus an exclusive
          benefit. When you choose to have your windshield replaced at our shop,
          you'll receive <SpanText>6 months free</SpanText> unlimited rock chip
          repair with any replacement. Enjoy the peace of mind that comes with
          knowing your windshield is protected long after the initial service.
          Visit us today for expert care and added value!
        </InfoText>
      </InfoContainer>
      <CarContainer>
        <img src={SedanImg} alt="" />
      </CarContainer>
    </AboutUsContainer>
  );
};

export default AboutSection;
